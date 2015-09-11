<?php
namespace App;

/**
 * Interface ExportableModelInterface
 * @author SL
 * @package App
 */
interface ExportableModelInterface
{
    /**
     * @return mixed
     */
    public function getExportableFields();
}
