<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

use Orchid\Screen\AsSource;

class FundImport extends Model
{
    use Filterable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'import_funds';

    const IMPORTING = 'importing';
    const PENDING = 'pending';
    const APPROBED = 'approved';

    const STATUSES = [
        self::IMPORTING => 'importing',
        self::PENDING => 'pending',
        self::APPROBED => 'approved',
    ];

    const STATUS_KEYS = [
        self::IMPORTING,
        self::PENDING,
        self::APPROBED,
    ];

    protected $fillable = [
        'status',
        'quy_lkdv_id',
        'approved_at',
    ];

    public function fund()
    {
        return $this->belongsTo(Fund::class, 'quy_lkdv_id', 'id');
    }
}
