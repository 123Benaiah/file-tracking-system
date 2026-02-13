<?php

namespace App\Livewire\Files;

use App\Models\File;
use App\Traits\WithToast;
use Livewire\Component;

class TrackFile extends Component
{
    use WithToast;

    public $fileNumber = '';

    public $searchResults = null;

    public $selectedFile = null;

    protected $rules = [
        'fileNumber' => 'required|string',
    ];

    public function search()
    {
        $this->validate();

        $searchTerm = '%'.$this->fileNumber.'%';

        $this->searchResults = File::where('status', '!=', 'merged')
            ->where(function ($q) use ($searchTerm) {
                $q->where('new_file_no', 'like', $searchTerm)
                    ->orWhere('old_file_no', 'like', $searchTerm)
                    ->orWhere('original_file_no', 'like', $searchTerm)
                    ->orWhere('subject', 'like', $searchTerm)
                    ->orWhere('file_title', 'like', $searchTerm);
            })
            ->with(['currentHolder', 'registeredBy'])
            ->get();

        if ($this->searchResults->count() === 1) {
            $this->viewDetails($this->searchResults->first()->id);
        } elseif ($this->searchResults->count() === 0) {
            $this->toastWarning('No Results', 'No files found matching your search.');
        } else {
            $this->toastInfo('Search Results', $this->searchResults->count().' files found matching your search.');
        }
    }

    public function viewDetails($fileId)
    {
        $this->selectedFile = File::with([
            'currentHolder',
            'registeredBy',
            'movements' => function ($query) {
                $query->orderBy('sent_at', 'desc');
            },
            'movements.sender',
            'movements.intendedReceiver',
            'movements.actualReceiver',
        ])->findOrFail($fileId);
    }

    public function clearSearch()
    {
        $this->reset(['fileNumber', 'searchResults', 'selectedFile']);
    }

    public function clearDetails()
    {
        $this->reset(['selectedFile']);
    }

    public function render()
    {
        return view('livewire.files.track-file');
    }
}
