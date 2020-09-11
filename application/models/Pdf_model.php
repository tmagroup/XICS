<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Pdf_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    //Employee Commission Slip PDF
    public function pdf_employeecommissionslip($data){
        $this->load->view('admin/pdfs/pdf_employeecommissionslip',$data);
    }

    //Print Quotation PDF
    public function pdf_printquotation($data){
        $this->load->view('admin/pdfs/pdf_printquotation',$data);
    }

    //Print Hardware Quotation PDF
    public function pdf_printhardwarequotation($data){
        $this->load->view('admin/pdfs/pdf_printhardwarequotation',$data);
    }

    //Print Consultation Protocol PDF
    public function pdf_printconsultationprotocol($data){
        $this->load->view('admin/pdfs/pdf_printconsultationprotocol',$data);
    }

    //Print Invoice Protocol PDF
    public function pdf_printinvoiceprotocol($data){
        $this->load->view('admin/pdfs/pdf_printinvoiceprotocol',$data);
    }

    //Print Delivery Note PDF
    public function pdf_printdeliverynote($data){
        $this->load->view('admin/pdfs/pdf_printdeliverynote',$data);
    }

    //Print Invoice PDF
    public function pdf_printhardwareinvoice($data){
        $this->load->view('admin/pdfs/pdf_printhardwareinvoice',$data);
    }

    //Print Delivery Note2 PDF
    public function pdf_printdeliverynote2($data){
        $this->load->view('admin/pdfs/pdf_printdeliverynote2',$data);
    }

    //Print Monitoring Protocol PDF
    public function pdf_printmonitoringprotocol($data){
        $this->load->view('admin/pdfs/pdf_printmonitoringprotocol',$data);
    }

    //Print Lead Quotation PDF
    public function pdf_printleadquotation($data){
        $this->load->view('admin/pdfs/pdf_printleadquotation',$data);
    }

    //Print Hardware Lead Quotation PDF
    public function pdf_printhardwareleadquotation($data){
        $this->load->view('admin/pdfs/pdf_printhardwareleadquotation',$data);
    }

    //Print Consultation Protocol PDF
    public function pdf_printconsultationprotocollead($data){
        $this->load->view('admin/pdfs/pdf_printconsultationprotocollead',$data);
    }

    //Print Invoice Protocol PDF
    public function pdf_printinvoiceprotocollead($data){
        $this->load->view('admin/pdfs/pdf_printinvoiceprotocollead',$data);
    }

    // monitoring pdf
    public function pdf_printmonitoringprovider($data){
        $this->load->view('admin/pdfs/pdf_printmonitoringprovider',$data);
    }
}
