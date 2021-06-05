<?php
class RoughModel extends CI_Model 
{
    function __construct() {
        parent::__construct(); 
    }
    function allposts_count()
    {   
        $this->db->where('status','1');
        $query = $this->db->get('rough_payment');
        return $query->num_rows();  
    }
    function allposts($limit,$start,$col,$dir)
    {   
       $this->db->where('status','1');
       $query = $this->db->limit($limit,$start)->order_by($col,$dir)->get('rough_payment');
        if($query->num_rows()>0)
        {
            return $query->result(); 
        }
        else
        {
            return null;
        }
    }
    function posts_search($limit,$start,$search,$col,$dir)
    {   
        $query=$this->db->select('*')->from('rough_payment')
                ->group_start()
                        ->like('party_name',$search)
                        ->or_like('date',$search)
                        ->or_like('remark',$search)
                        ->or_like('rs',$search)
                ->group_end()
                ->where('status', '1')
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->get();
        if($query->num_rows()>0)
        {
            return $query->result();  
        }
        else
        {
            return null;
        }
    }
    function posts_search_count($search)
    {
        $query=$this->db->select('*')->from('rough_payment')
                ->group_start()
                        ->like('party_name',$search)
                        ->or_like('date',$search)
                        ->or_like('remark',$search)
                        ->or_like('rs',$search)
                ->group_end()
                ->where('status', '1')
                ->get();
        return $query->num_rows();
    } 
}