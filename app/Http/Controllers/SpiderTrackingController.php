<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class SpiderTrackingController extends Controller
{
    public function index()
    {
        $logPath = storage_path('logs/google_spiders.log'); // Adjust the path as needed
        $spiderVisits = $this->parseLogFile($logPath);
        $spiderDetails = $this->getSpiderDetails($logPath, 1000); // Get latest 1000 spider visit details
        $todaysTotal = $this->calculateTodaysTotal($logPath);
        $yesterdaysTotal = $this->calculateYesterdaysTotal($logPath);

        return view('spider_tracking', [
            'spiderVisits' => $spiderVisits,
            'spiderDetails' => $spiderDetails,
            'todaysTotal' => $todaysTotal,
            'yesterdaysTotal' => $yesterdaysTotal
        ]);
    }

    private function getSpiderDetails($filePath, $limit)
    {
        $details = [];

        if (File::exists($filePath)) {
            $file = File::get($filePath);
            $lines = explode("\n", $file);
            $lines = array_reverse($lines); // Reverse to get the latest entries first

            foreach ($lines as $line) {
                if (count($details) >= $limit) {
                    break;
                }
                if (strpos($line, 'Google Spider Detected') !== false) {
                    $decodedLine = json_decode($this->extractJson($line), true);
                    $decodedLine['time'] = $this->extractTime($line); // Extract and add time
                    $details[] = $decodedLine;
                }
            }
        }

        return $details;
    }
    
    private function extractTime($line)
    {
        preg_match('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $line, $matches);
        return $matches[1] ?? now()->format('Y-m-d H:i:s');
    }

    private function extractJson($string)
    {
        preg_match('/\{(?:[^{}]|(?R))*\}/x', $string, $match);
        return $match[0] ?? '{}';
    }

    private function parseLogFile($filePath)
    {
        $googleSpiderPattern = 'Google Spider Detected';
        $visitsCount = [];

        if (File::exists($filePath)) {
            $file = File::get($filePath);

            // Split the file into lines
            $lines = explode("\n", $file);

            foreach ($lines as $line) {
                if (strpos($line, $googleSpiderPattern) !== false) {
                    // Extract the date and hour from the log line
                    $dateHour = $this->extractDateHour($line);

                    if (!isset($visitsCount[$dateHour])) {
                        $visitsCount[$dateHour] = 0;
                    }

                    $visitsCount[$dateHour]++;
                }
            }
        }

        return $visitsCount;
    }

    private function extractDateHour($line)
    {
        // Extract date and hour in format '2023-12-23 10'
        preg_match('/\[(\d{4}-\d{2}-\d{2} \d{2})/', $line, $matches);
        return $matches[1] ?? now()->format('Y-m-d H');
    }

    private function extractDate($line)
    {
        preg_match('/\[(\d{4}-\d{2}-\d{2})/', $line, $matches);
        return $matches[1] ?? now()->format('Y-m-d');
    }

    // Adjust the calculateTodaysTotal and calculateYesterdaysTotal methods to use extractDate
    private function calculateTodaysTotal($filePath)
    {
        $today = now()->format('Y-m-d');
        $count = 0;
        $file = File::exists($filePath) ? File::get($filePath) : '';

        foreach (explode("\n", $file) as $line) {
            if (strpos($line, 'Google Spider Detected') !== false && $this->extractDate($line) == $today) {
                $count++;
            }
        }

        return $count;
    }

    private function calculateYesterdaysTotal($filePath)
    {
        $yesterday = now()->subDay()->format('Y-m-d');
        $count = 0;
        $file = File::exists($filePath) ? File::get($filePath) : '';

        foreach (explode("\n", $file) as $line) {
            if (strpos($line, 'Google Spider Detected') !== false && $this->extractDate($line) == $yesterday) {
                $count++;
            }
        }

        return $count;
    }

}
