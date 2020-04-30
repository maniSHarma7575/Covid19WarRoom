<?php
class Helpers
{
    public static function documentValidation($size_of_uploaded_file, $max_allowed_file_size, $allowed_extensions, $type_of_uploaded_file)
    {
        $errors = '';
        if ($size_of_uploaded_file > $max_allowed_file_size) {
            $errors .= "\n Size of file should be less than $max_allowed_file_size";
        }


        $allowed_ext = false;
        for ($i = 0; $i < sizeof($allowed_extensions); $i++) {
            if (strcasecmp($allowed_extensions[$i], $type_of_uploaded_file) == 0) {
                $allowed_ext = true;
            }
        }

        if (!$allowed_ext) {
            $errors .= "The uploaded file is not supported file type. " .
                " Only the following file types are supported: " . implode(',', $allowed_extensions);
        }
        return $errors;
    }
}
