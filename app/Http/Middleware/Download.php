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
use App\ExportableModelInterface;
use Illuminate\Database\Eloquent\Model;
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
//           $customFilename =  array_key_exists('custom_filename', $response->original->getData())
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

                    $csv_headers_set = false;

                    foreach ($response->original->getData()['api_data'] as $data) {
                        if(!$csv_headers_set){
                            $writer->insertOne(array_keys($this->getArrayRepresentation($data)));
                            $csv_headers_set = true;
                        }
                        $writer->insertOne($this->processData($this->getArrayRepresentation($data)));
                    }

                    return response()->make($writer, 200, $headers);
                default:
                    throw RedirectException::make('/')->setError('Unrecognised type to download');
            }
        }

        return $response;
    }

    /**
     * @param array $data
     * @return array
     */
    private function processData(array $data)
    {
        foreach ($data as &$row) {

            if (is_array($row)) {

                if (isset($row['ext_id'])) {
                    $row = $row['ext_id'];
                    continue;
                }

                if (isset($row['id'])) {
                    $row = $row['id'];
                    continue;
                }

                $row = '';
            }
        }

        return $data;
    }

    /**
     * Return an array representation of the given data $model based on it's implementation.
     * @author SL, EA
     * @param $model
     * @return array
     * @throws \Exception
     */
    private function getArrayRepresentation($model){

        if ($model instanceof ExportableModelInterface){

            return $model->getExportableFields();
        }

        if ($model instanceof Model) {

            return $model->toArray();
        }

        if (is_array($model)) {

            return $model;
        }

        throw new \Exception('Unable to determine type in Download@getArrayRepresentation()');
    }
}
