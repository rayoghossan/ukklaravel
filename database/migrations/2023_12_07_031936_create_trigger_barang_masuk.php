<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateTriggerBarangMasuk extends Migration
{
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER barang_up_stokplus 
        AFTER INSERT ON barangmasuk
        FOR EACH ROW
           BEGIN
             UPDATE barang SET barang.stok = barang.stok + NEW.qty_masuk WHERE   barang.id=NEW.barang_id; 
          END
        ');
    }

    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS kurangi_stok_setelah_masuk');
    }
}
