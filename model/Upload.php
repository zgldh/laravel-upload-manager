<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Upload
 * @property string $name
 * @property string $description
 * @property string $disk
 * @property string $path
 * @property string $size
 * @property string $user_id
 */
class Upload extends Model
{
    protected $table = 'uploads';

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function getUrlAttribute()
    {
        if ($this->disk == 'qiniu') {
            $disk = \Storage::disk($this->disk);
            return $disk->getDriver()->downloadUrl($this->path);
        } else {
            return url('uploads/' . $this->path);
        }
    }

    public function deleteFile()
    {
        if ($this->path) {
            $disk = \Storage::disk($this->disk);
            if ($disk->exists($this->path)) {
                $disk->delete($this->path);
                $this->path = '';
                $this->save();
            }
        }
    }

    public function isInDisk($diskName)
    {
        return $this->disk == $diskName ? true : false;
    }

    public function moveToDisk($newDiskName)
    {
        if ($newDiskName == $this->disk) {
            return true;
        }
        $currentDisk = \Storage::disk($this->disk);
        $content = $currentDisk->get($this->path);
        $newDisk = \Storage::disk($newDiskName);
        $newDisk->put($this->path, $content);
        if ($newDisk->exists($this->path)) {
            $this->disk = $newDiskName;
            $this->save();
            return true;
        }
        return false;
    }
}
