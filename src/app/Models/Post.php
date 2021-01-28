<?php

namespace CMS\Backpack\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;

class Post extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use RevisionableTrait;
    use HasFactory;
    protected $revisionEnabled = true;
    protected $revisionCleanup = true;
    protected $historyLimit = 500; //Stop tracking revisions after 500 changes have been made.
    protected $table="posts";
    protected $primaryKey = "id";
    protected $fillable=["title","user_id","url","description","excerpt","image","status","format_id","allow_comments","disk","custom_fields"];
    protected $casts = [
        'custom_fields' => 'array',
    ];
    public function category()
    {
        return $this->morphToMany(
            Category::class,
            'cateable',
            "cateable",
            "cateable_id",
            'category_id'
        );
    }
    public function User(){
        return $this->belongsTo(User::class,"user_id","id");
    }
    public function tag()
    {
        return $this->morphToMany(
            Tag::class,
            'taggable',
            "taggable",
           "taggable_id",
            'tag_id'
        );
    }
    public function Format(){
        return $this->belongsTo(Format::class,"format_id","id");
    }
    public function setImageAttribute($value)
    {
        $attribute_name = "image";
        $disk = config("save_disk.post_thumb");
        $destination_path = "/";
        if ($value==null) {
            // delete the image from disk
            \Storage::disk($disk)->delete($this->{$attribute_name});

            // set null in the database column
            $this->attributes[$attribute_name] = null;
        }
        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    }

}
