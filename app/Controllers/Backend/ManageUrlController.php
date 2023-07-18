<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;
use App\Models\Url;

class ManageUrlController extends BaseController
{
    public function index()
    {
        helper('url');
        $url = new Url();
        $urls = $url->where('user_id', session()->get('auth')['id'])->findAll();
        return view('backend/manage-url/list' , compact('urls'));
    }



    public function create()
    {
        helper('url');
        $url = new Url();
        $this->validate([
            'url' => 'required|valid_url'
        ]);
        $shorten_url = $this->ShortUrl();
        // var_dump(base_url() . '/' . $shorten_url);die();
        $data = [
            'url' => $this->request->getPost('url'),
            'short_url' => base_url() . $shorten_url,
            'code' => $shorten_url,
            'user_id' => $this->request->getPost('user_id') ?? session()->get('auth')['id'],
            'created_at' => date('Y-m-d H:i:s')
        ];
        try{
            $url->db->transStart();
            $url = new Url();
            $result = $url->insert($data);
            if($result){
                $url->db->transCommit();
                return redirect()->to(base_url('manage-url'))->with('success', 'Url created successfully');
            }
            return redirect()->to(base_url('manage-url'))->with('error', 'Url failed to create');
        } catch (\Exception $e) {
            $url->db->transRollback();
            $this->logger->log('error', $e->getMessage());
            return redirect()->to(base_url('manage-url'))->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $url = new Url();
        $url = $url->where('code', $id)->first();
        return view('backend/manage-url/edit' , compact('url'));
    }

    public function update($id)
    {
        $url = new Url();
        $url = $url->where('id', $id)->first();
        var_dump($url);die();
        $this->validate([
            'origin_url' => 'required|valid_url',
            'shorten_url' => 'required|valid_url',
            'code' => 'required',
            'clicks' => 'required',
            'status' => 'required',
        ]);
        $data = [
            'origin_url' => $this->request->getPost('origin_url'),
            'shorten_url' => $this->request->getPost('shorten_url'),
            'code' => $this->request->getPost('code'),
            'clicks' => $this->request->getPost('clicks'),
            'status' => $this->request->getPost('status'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        try{
            $url->db->transStart();
            $result = $url->updateRecordWithTransaction($id, $data);
            if($result){
                $url->db->transCommit();
                return redirect()->to(base_url('manage-url'))->with('success', 'Url updated successfully');
            }
            return redirect()->to(base_url('manage-url'))->with('error', 'Url failed to update');
        } catch (\Exception $e) {
            $url->db->transRollback();
            $this->logger->log('error', $e->getMessage());
            return redirect()->to(base_url('manage-url'))->with('error', $e->getMessage());
        }
    }

    public function delete($id)
    {
        var_dump($id);die();
        $url = new Url();
        try{
            $url->db->transStart();
            $result = $url->deleteRecordWithTransaction($id);
            if($result){
                $url->db->transCommit();
                return redirect()->to(base_url('manage-url'))->with('success', 'Url deleted successfully');
            }
            return redirect()->to(base_url('manage-url'))->with('error', 'Url failed to delete');
        } catch (\Exception $e) {
            $url->db->transRollback();
            $this->logger->log('error', $e->getMessage());
            return redirect()->to(base_url('manage-url'))->with('error', $e->getMessage());
        }
    }

    public function ShortUrl(){
        $url = new Url();
        $result = base_convert(rand(1000,99999), 10, 36);
        $check = $url->where('code', $result)->first();

        if($check != null){
            $this->ShortUrl();
        }

        return $result;
    }
}
