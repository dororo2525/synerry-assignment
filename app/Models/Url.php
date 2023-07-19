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
        $urls = new Url();
        $urlclicks = new UrlClick();
        $url = $urls->where('code', $code)->first();
        $columns = ['platform', 'browser', 'device'];
        $urlclick = $this->getCountByColumns($columns , $url['id']);
        $currentYear = date('Y');
        $currentClicks = [];

        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::createFromDate($currentYear, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($currentYear, $month, 1)->endOfMonth();
            $clickCount = $urlclicks->where('shorten_url_id', $url['id'])->where("DATE_FORMAT(created_at, '%Y-%m-%d') BETWEEN  '{$startDate->toDateString()}'  AND   '{$endDate->toDateString()}'")->countAllResults();
            $currentClicks[$month-1] = [
                'clicks' => $clickCount
            ];
        }
        return [
            'months' => $currentClicks,
            'urlclicks' => $urlclick
        ];
    }

    public function countClicksByMonthRange($startDate, $endDate)
    {
        $startDate = Carbon::parse($startDate)->startOfMonth();
        $endDate = Carbon::parse($endDate)->endOfMonth();
        $clickCounts = [];
        $currentDate = $startDate->copy();
        $urls = $this->where('user_id', session()->get('auth')['id'])->findAll();
        $urlclick = new UrlClick();
        $startMonth = 1;
        $startYear = 2022;
        $endMonth = 12;
        $endYear = 2022;
        
        // $startDate = Carbon::createFromDate($startYear, $startMonth, 1)->startOfMonth();
        // $endDate = Carbon::createFromDate($endYear, $endMonth, 1)->endOfMonth();
        
        $dates = [];
        
        $currentDate = $startDate->copy();
        
        while ($currentDate->lte($endDate)) {
            $start = $currentDate->copy()->startOfMonth();
            $end = $currentDate->copy()->endOfMonth();
        
            $dates[$currentDate->format('Y-m')] = [
                'start' => $start->toDateString(),
                'end' => $end->toDateString(),
            ];
        
            $currentDate->addMonth();
        }
        return $urls;
    }

    public function getCountByColumns($columns , $id)
    {
        $urlclick = new UrlClick();
        $result = [];
        $mergedRow = [];
        foreach ($columns as $column) {
                $result[$column] = $urlclick->select($column . ', COUNT(*) as count_' . $column)->where('shorten_url_id' , $id)->groupBy($column)->findAll();
        }

        return $result;

    }

    public function user()
    {
        return $this->belongsTo('App\Model\User', 'user_id', 'id');
    }

    public function clicks()
    {
        return $this->hasMany('App\Model\UrlClick', 'shorten_url_id', 'id');
    }
}
