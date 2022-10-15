<?php

namespace Laravel\Nova\LogViewer\Http\Controllers\Pages;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File as FileFacade;
use Inertia\Inertia;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\LogViewer\File;
use Symfony\Component\Finder\SplFileInfo;

class LogViewerController extends Controller
{
    /**
     * Show the log viewer index.
     *
     * @return \Inertia\Response
     */
    public function __invoke()
    {
        $logs = collect(FileFacade::allFiles(storage_path('logs')))
            ->filter(fn (SplFileInfo $log) => $log->getExtension() === 'log')
            ->map(function (SplFileInfo $log) {
                return [
                    'label' => $log->getRelativePathname(),
                    'value' => $log->getRelativePathname(),
                ];
            })
            ->sortByDesc('label')
            ->values();

        return Inertia::render('NovaLogViewer', [
            'logs' => $logs,
        ]);
    }

    /**
     * Fetch the latest content for a log.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetch(NovaRequest $request)
    {
        $request->validate(['lastLine' => ['numeric']]);
        $logFile = new File(storage_path('logs/' . $request->log));
        $lines = $logFile->contentAfterLine($request->lastLine);
        $lastLine = $request->lastLine + substr_count($lines, PHP_EOL);

        return response()->json([
            'lastLine' => $lastLine,
            'content' => $lines,
            'numberOfLines' => $logFile->numberOfLines(),
        ]);
    }
}
