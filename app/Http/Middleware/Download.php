<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Middleware;

use App\Exceptions\RedirectException;
use Closure;
use League\Csv\Writer;

/**
 * Download
 *
 * @author WN
 * @package App\Http\Middleware
 */
class Download
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     * @throws RedirectException
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($request->get('download') && array_key_exists('api_data', $response->original->getData())) {

            switch ($request->get('download')) {
                case 'json':
                    return response()->json(
                        $response->original->getData()['api_data'], 200,
                        ['Content-Disposition' => 'attachment; filename="export_' . date('Y-m-d_Hi') . '.json"']
                    );
                case 'csv':

                    $writer = Writer::createFromFileObject(new \SplTempFileObject());

                    $writer->setDelimiter(',');
                    $writer->setNewline("\r\n");
                    $writer->setEncodingFrom("utf-8");

                    $headers = [
                        'Content-Type' => 'text/csv',
                        'Content-Disposition' => 'attachment; filename="export_' . date('Y-m-d_Hi') . '.csv"',
                    ];

                    foreach ($response->original->getData()['api_data'] as $data) {

                        $writer->insertOne($data->toArray());
                    }

                    return response()->make($writer, 200, $headers);
                default:
                    throw RedirectException::make('/')->setError('Unrecognised type to download');
            }
        }

        return $response;
    }
}
