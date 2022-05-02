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
        $logs = FileFacade::allFiles(storage_path('logs'));

        return Inertia::render('NovaLogViewer', [
            'logs' => collect($logs)->map(function (SplFileInfo $log) {
                return [
                    'label' => $log->getRelativePathname(),
                    'value' => $log->getPathname(),
                ];
            }),
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
        $logFile = new File($request->log);
        $lines = $logFile->contentAfterLine($request->lastLine);
        $lastLine = $request->lastLine + substr_count($lines, PHP_EOL);

        return response()->json([
            'lastLine' => $lastLine,
            'content' => $lines,
            'numberOfLines' => $logFile->numberOfLines(),
        ]);
    }
}
