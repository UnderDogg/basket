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
     * @return array
     */
    public function getExportableFields();
}
