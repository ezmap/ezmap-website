<?php
if (!function_exists('mix'))
{
  /**
   * Get the path to a versioned Elixir file.
   *
   * @param string $file
   * @param string $buildDirectory
   * @return string
   * @throws \InvalidArgumentException
   */
  function mix($file)
  {
    static $manifest;
    static $manifestPath;

    if (is_null($manifest))
    {
      $manifest = json_decode(file_get_contents(public_path('/mix-manifest.json')), true);
    }

    if (isset($manifest[$file]))
    {
      return '/' . trim($manifest[$file], '/');
    }

    throw new InvalidArgumentException("File {$file} not defined in asset manifest.");
  }
}
