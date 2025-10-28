<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ErrorLogViewer extends Component
{

    public $logFile = 'laravel.log';
    public $logLevel = 'all';
    public $search = '';
    public $linesPerPage = 50;
    public $logEntries = [];
    public $totalLines = 0;
    public $currentPage = 1;
    public $logFileSize = 0;
    public $lastModified = null;

    protected $queryString = [
        'logLevel' => ['except' => 'all'],
        'search' => ['except' => ''],
        'linesPerPage' => ['except' => 50],
    ];

    public function mount()
    {
        // Ensure only super users can access this
        if (!auth()->user()->hasRole('system-admin')) {
            abort(403, 'Unauthorized access to error logs.');
        }
        
        $this->loadLogEntries();
    }

    public function updatedLogLevel()
    {
        $this->currentPage = 1;
        $this->loadLogEntries();
    }

    public function updatedSearch()
    {
        $this->currentPage = 1;
        $this->loadLogEntries();
    }

    public function updatedLinesPerPage()
    {
        $this->currentPage = 1;
        $this->loadLogEntries();
    }

    public function clearLogs()
    {
        $logPath = storage_path('logs/' . $this->logFile);
        
        if (File::exists($logPath)) {
            File::put($logPath, '');
            $this->currentPage = 1;
            $this->loadLogEntries();
            session()->flash('message', 'Log file cleared successfully.');
        } else {
            session()->flash('error', 'Log file not found.');
        }
    }

    public function downloadLogs()
    {
        $logPath = storage_path('logs/' . $this->logFile);
        
        if (!File::exists($logPath)) {
            session()->flash('error', 'Log file not found.');
            return;
        }

        $content = File::get($logPath);
        $fileSize = File::size($logPath);
        $fileSizeFormatted = $fileSize > 1024 ? number_format($fileSize / 1024, 2) . 'KB' : $fileSize . 'B';
        $filename = 'error-logs-' . date('Y-m-d-H-i-s') . '-' . $fileSizeFormatted . '.log';
        
        // Flash a success message before starting the download
        session()->flash('message', 'Download started. File: ' . $filename);
        
        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $filename, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function loadLogEntries()
    {
        $logPath = storage_path('logs/' . $this->logFile);
        
        if (!File::exists($logPath)) {
            $this->logEntries = [];
            $this->totalLines = 0;
            $this->logFileSize = 0;
            $this->lastModified = null;
            return;
        }

        // Get file information
        $this->logFileSize = File::size($logPath);
        $this->lastModified = File::lastModified($logPath);

        $content = File::get($logPath);
        $lines = explode("\n", $content);
        
        // Filter out empty lines
        $lines = array_filter($lines, function($line) {
            return !empty(trim($line));
        });

        // Filter by log level
        if ($this->logLevel !== 'all') {
            $lines = array_filter($lines, function($line) {
                return strpos($line, '] ' . strtoupper($this->logLevel) . ':') !== false;
            });
        }

        // Filter by search term
        if (!empty($this->search)) {
            $lines = array_filter($lines, function($line) {
                return stripos($line, $this->search) !== false;
            });
        }

        $this->totalLines = count($lines);
        
        // Paginate the results
        $offset = ($this->currentPage - 1) * $this->linesPerPage;
        $this->logEntries = array_slice($lines, $offset, $this->linesPerPage);
    }

    public function nextPage()
    {
        if ($this->currentPage * $this->linesPerPage < $this->totalLines) {
            $this->currentPage++;
            $this->loadLogEntries();
        }
    }

    public function previousPage()
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
            $this->loadLogEntries();
        }
    }

    public function render()
    {
        return view('livewire.admin.error-log-viewer')
            ->layout('components.layouts.app');
    }
}
