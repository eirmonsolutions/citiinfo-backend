<?php
// app/Models/FAQItem.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FAQItem extends Model
{
    protected $table = 'faq_items';
    protected $fillable = ['faq_id', 'question', 'answer', 'sort_order'];

    public function faq()
    {
        return $this->belongsTo(FAQ::class, 'faq_id');
    }
}
