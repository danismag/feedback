<?php

namespace App\Models;

/**
*   Класс загрузки и обработки изображений
*/
class Image
{
    const WIDTH = 320;          // требуемые максимальные размеры
    const HEIGHT = 240;
    
    public $imagepath;          // относительный путь к сохраненной картинке
    private $fullpath;          // полный путь к файлу изображения
    
    /**
    *   Обработка картинки и создание объекта
    *   
    *   @param array $file - массив $_FILES['image']
    *   @return Image $image - объект класса Image или null
    */
    public static function create($file)
    {
        // загрузки изображения не произошло
        if (!isset($file)) {
            return null;            
        }
        
        // проверка на ошибки загрузки
        if ($file['error'] == UPLOAD_ERR_OK) {

            // ограничения на размер файла не больше 4 Мб
            if ($file['size'] > 4194304) {
                return null;
            }

            //  определение типа файла и расширения
            switch ($file['type']) {
                
                case 'image/jpeg':
                    $ext = 'jpg';                    
                    break;
                case 'image/png':
                    $ext = 'png';
                    break;
                case 'image/gif':
                    $ext = 'gif';
                    break;               
            }
            
            // сохранение загруженного файла под сгенерированным именем
            if ($ext) {                
                $name = self::generateName($ext);
                
                if (move_uploaded_file($file['tmp_name'], $name)) {
                    
                    return new self($name);
                }            
            }            
        }
        
        return null;
    }
    
    /**
    *   удаление изображения с сервера
    */
    public function clear()
    {
        
        $this->delete($this->fullpath);
    }
    
    /**
    *   Генерация уникального имени файла
    *   @param string $ext расширение создаваемого файла
    *   @return string полный путь к файлу
    **/
    private static function generateName($ext)
    {
        return (BASE_PATH . '/images/'. uniqid() . ".$ext");
    }
    
    /**
    *   Конструктор
    *   проверяет размеры картинки и формирует окончательное имя файла
    *
    *   @param string $image полный путь к сохраненному файлу
    */
    private function __construct($image)
    {
        $name = $this->resize($image);
        
        if ($name){ 
            if ($name == $image) {
                
                $this->fullpath = $image;
                $this->imagepath = substr($image, strlen(BASE_PATH));
                           
            } elseif (file_exists($name)) {
                
                $this->delete($image);
                $this->fullpath = $name;
                $this->imagepath = substr($name, strlen(BASE_PATH));
            }
        }
    }
  
    /**
    *   Пропорциональное уменьшение картинки
    *   
    *   @param string $image - путь к картинке
    *   @return string - путь к файлу с размерами, соотв. требованиям либо false
    */
    private function resize($image)
    {
        // цвет фона для сжатых jpg и gif-изображений
        $rgb = 0xffffff;

        // проверка размеров на соответствие
        $size = getimagesize($image);
        // ошибка в определении размеров или формате файла
        if (!$size) {
            return false;
        }
        if ($size[0] == 0 || $size[1] == 0) {
            return false;
        }

        // Если не требуется изменение размеров картинки
        if ($size[0] < self::WIDTH && $size[1] < self::HEIGHT) {
            
            return $image;
        }

        // Если требуется изменение размеров картинки
        // вычисляем пропорции ширины и высоты по отношению к требуемой
        $x_ratio = self::WIDTH / $size[0];
        $y_ratio = self::HEIGHT / $size[1];

        // выбираем пропорцию уменьшения картинки
        $ratio = min($x_ratio, $y_ratio);
        $use_x_ratio = ($x_ratio == $ratio);

        // новые размеры картинки
        $new_width = $use_x_ratio ? self::WIDTH : floor($size[0] * $ratio);
        $new_height = !$use_x_ratio ? self::HEIGHT : floor($size[1] * $ratio);

        // расхождение с заданными параметрами по ширине и высоте
        $new_left = $use_x_ratio ? 0 : floor((self::WIDTH - $new_width)/2);
        $new_top = !$use_x_ratio ? 0 : floor((self::HEIGHT - $new_height)/2);

        // создаем холст требуемого размера
        $img = imagecreatetruecolor(self::WIDTH, self::HEIGHT);
        
        $ext = $this->getFormat($image);
        
        switch ($ext) {

            case 'jpg':
                // заливаем холст цветом фона
                imagefill($img, 0, 0, $rgb);
                // загружаем исходную картинку
                $photo = imagecreatefromjpeg($image);
                break;

            case 'gif':
                // заливаем холст цветом фона
                imagefill($img, 0, 0, $rgb);
                // загружаем исходную картинку
                $photo = imagecreatefromgif($image);
                break;

            case 'png':
                // делаем фон прозрачным
                imagealphablending($img, false);
                imagesavealpha($img, true);
                // загружаем исходную картинку
                $photo = imagecreatefrompng($image);
        }

        // копируем на холст сжатую картинку с учетом расхождений
        imagecopyresampled($img, $photo, $new_left, $new_top, 0, 0, $new_width,
            $new_height, $size[0], $size[1]);

        // сохраняем результат под сгенерированным именем
        $path = self::generateName($ext);

        switch ($ext) {

            case 'jpg':
                $result = imagejpeg($img, $path);
                break;

            case 'gif':
                $result = imagegif($img, $path);
                break;

            case 'png':
                $result = imagepng($img, $path);
        }

        // очищаем память
        imagedestroy($img);
        imagedestroy($photo);

        if ($result) {
            
            return $path;
        }
        return false;
    }

    /**
    *   Проверка формата изображения
    *   @param string $image - путь к файлу изображения
    *   @return string - строка вида 'jpg', 'gif', 'png' или false
    */
    private function getFormat($image)
    {

        $size = getimagesize($image);
        if (!$size) {
            return false;
        }

        $format = strtolower(substr($size['mime'], strpos($size['mime'], '/') + 1));
        
        if ('jpeg' == $format) {
            
            $format = 'jpg';
        }
        
        return $format;
    }

        
    /**
    *   Удаление изображения
    *    
    *   @param string $image полное имя файла (путь)
    */
    private function delete($image)
    {
        unlink($image);
    }
}

?>