<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\UrlClick;
use Carbon\Carbon;
use CodeIgniter\I18n\Time;

class Url extends Model
{
    // protected $DBGroup          = 'default';
    protected $table            = 'shorten_urls';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'url',
        'short_url',
        'code',
        'clicks',
        'user_id',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function countClicksByCurrentYear($code)
    {
        
        $currentYear = Carbon::now()->year;
$urlclick = new UrlClick();
$months = [];

        $startDate = Time::parse('2023-01-01');
        $endDate = Time::parse('2023-12-31');

        // Loop through each month within the date range
        while ($startDate->isBefore($endDate)) {
           $months[] = $startDate;
            $startDate->modify('+1 month');
        }
        return $months;
    }

    public function countClicksByMonthRange($startDate, $endDate)
    {
        $startDate = Carbon::parse($startDate)->startOfMonth();
        $endDate = Carbon::parse($endDate)->endOfMonth();
        $clickCounts = [];
        $currentDate = $startDate->copy();
        $urls = $this->where('user_id', session()->get('auth')['id'])->findAll();
        $urlclick = new UrlClick();
        foreach ($urls as $key => $url) {
            while ($currentDate <= $endDate) {
                $clickCount = $urlclick->where('created_at BETWEEN "' . $currentDate->year . '" AND "' . $currentDate->month)->where('shorten_url_id',$urls['id'])->count();

                $clickCounts[$currentDate->format('Y-n')] = $clickCount;

                $currentDate->addMonth();
            }
            $urls[$key]['clicks'] = $clickCounts;
            $clickCounts = [];
        }
        return $urls;
    }

    public function user(){
        return $this->belongsTo('App\Model\User' , 'user_id' , 'id');
    }

    public function clicks(){
        return $this->hasMany('App\Model\UrlClick' , 'shorten_url_id' , 'id');
    }
}
