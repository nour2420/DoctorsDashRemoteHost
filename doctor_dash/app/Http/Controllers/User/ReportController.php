<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $reports = $user->reports()->latest()->paginate(10);

        return view('user.reports.index', [
            'user' => $user,
            'reports' => $reports,
        ]);
    }

    public function create()
    {
        $user = Auth::user();

        return view('user.reports.create', [
            'user' => $user,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'files' => ['required', 'array'],
            // السماح بأي نوع ملف وأي حجم (الحد الفعلي يعتمد على إعدادات الخادم PHP)
            'files.*' => ['file'],
        ]);

        foreach ($data['files'] as $file) {
            $path = $file->store('reports', 'public');

            Report::create([
                'user_id' => $user->id,
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'redirect' => route('user.reports.index'),
                'message' => 'Case uploaded successfully.',
            ]);
        }

        return redirect()->route('user.reports.index')->with('status', 'Case uploaded successfully.');
    }

    public function edit(Request $request, Report $report)
    {
        $user = $request->user();

        abort_unless($report->user_id === $user->id, 404);

        return view('user.reports.edit', [
            'user' => $user,
            'report' => $report,
        ]);
    }

    public function update(Request $request, Report $report)
    {
        $user = $request->user();

        abort_unless($report->user_id === $user->id, 404);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            // السماح بأي نوع ملف وأي حجم (الحد الفعلي يعتمد على إعدادات الخادم PHP)
            'file' => ['nullable', 'file'],
        ]);

        if ($request->hasFile('file')) {
            if ($report->file_path) {
                Storage::disk('public')->delete($report->file_path);
            }

            $file = $data['file'];
            $path = $file->store('reports', 'public');

            $report->file_path = $path;
            $report->original_name = $file->getClientOriginalName();
            $report->mime_type = $file->getClientMimeType();
            $report->size = $file->getSize();
        }

        $report->title = $data['title'];
        $report->description = $data['description'] ?? null;
        $report->save();

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'redirect' => route('user.reports.index'),
                'message' => 'Case updated successfully.',
            ]);
        }

        return redirect()->route('user.reports.index')->with('status', 'Case updated successfully.');
    }

    public function destroy(Request $request, Report $report)
    {
        $user = $request->user();

        abort_unless($report->user_id === $user->id, 404);

        if ($report->file_path) {
            Storage::disk('public')->delete($report->file_path);
        }

        $report->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'redirect' => route('user.reports.index'),
                'message' => 'Case deleted successfully.',
            ]);
        }

        return redirect()->route('user.reports.index')->with('status', 'Case deleted successfully.');
    }
}
