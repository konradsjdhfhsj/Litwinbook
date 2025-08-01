<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class IssueController extends Controller
{
    public function issue(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body'  => 'nullable|string',
        ]);

        $title = $validated['title'];
        $body  = $validated['body'] ?? '';

        $token = env('GITHUB_TOKEN'); 
        $owner = 'konradsjdhfhsj';
        $repo  = 'Litwinbook';

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/vnd.github+json',
            'X-GitHub-Api-Version' => '2022-11-28',
        ])->post("https://api.github.com/repos/{$owner}/{$repo}/issues", [
            'title' => $title,
            'body'  => $body,
        ]);

        if ($response->successful()) {
            return back()->with('success', 'Issue utworzone pomyślnie!');
        }

        return back()->with('error', 'Błąd: '.$response->body());
    }
}
