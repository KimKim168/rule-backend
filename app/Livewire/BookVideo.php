<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BookVideo as MultiVideo;
use App\Models\Book;
use Livewire\WithFileUploads;
use Image;
use Illuminate\Support\Facades\File;

class BookVideo extends Component
{
    use WithFileUploads;

    public $image = null; // Updated to hold multiple images
    public $url = null;
    public $item;

    public function mount(Book $item)
    {
        $this->item = $item; // Initialize the $item variable with the provided item model instance
    }

    public function removeImage()
    {
        $this->image = null;
    }

    public function delete($id)
    {
        $image = MultiVideo::findOrFail($id);

        // Get the path to the image
        $imagePathThumb = public_path('assets/images/books/thumb/' . $image->image);
        $imagePath = public_path('assets/images/books/' . $image->image);

        // Delete the image file from the filesystem
        if (File::exists($imagePathThumb)) {
            File::delete($imagePathThumb);
        }
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }

        // Delete the record from the database
        $image->delete();

        session()->flash('success', 'File deleted successfully!');
    }

    public function updatedImage()
    {
        $this->validate([
            'image' => 'image|max:2048', // 2MB Max for each image
        ]);

        session()->flash('success', 'File uploaded successfully!');
    }

    public function save()
    {
        if (empty($this->image)) {
            session()->flash('error', ['Image is required!']);
            return;
        }

        $validated = $this->validate([
            'image' => 'image|max:2048', // 2MB Max for each image
            'url' => 'required|max:5048', // 2MB Max for each image
        ]);

        // dd($validated);

        // Ensure directories exist
        $filePath = public_path('assets/images/books');
        $fileThumbPath = public_path('assets/images/books/thumb');
        if (!File::exists($filePath)) {
            File::makeDirectory($filePath, 0755, true);
        }
        if (!File::exists($fileThumbPath)) {
            File::makeDirectory($fileThumbPath, 0755, true);
        }

        if (!empty($this->image)) {
            $filename = time() . '_' . $this->image->getClientOriginalName();

            $imagePath = $filePath . '/' . $filename;
            $imageThumbPath = $fileThumbPath . '/' . $filename;

            try {
                $imageUpload = Image::make($this->image->getRealPath())->save($imagePath);
                $imageUpload->resize(400, null, function ($resize) {
                    $resize->aspectRatio();
                })->save($imageThumbPath);

                MultiVideo::create([
                    'book_id' => $this->item->id,
                    'image' => $filename,
                    'url' => $this->url,
                ]);
                $this->url = '';
            } catch (\Exception $e) {
                session()->flash('error', ['An error occurred while saving the image.']);
                return;
            }
        }

        // Clear the images array
        $this->image = null;



        // session()->flash('success', 'Video saved successfully!');
        return redirect('/admin/book_videos/' . $this->item->id)->with('success', 'Video saved successfully!');
    }

    public function render()
    {
        $multiVideos = MultiVideo::where('book_id', $this->item->id)->latest()->get();
        return view('livewire.book-video', [
            'multiVideos' => $multiVideos,
        ]);
    }
}
