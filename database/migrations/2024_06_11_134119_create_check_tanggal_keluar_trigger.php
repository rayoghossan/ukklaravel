<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateCheckTanggalKeluarTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE TRIGGER time_travel BEFORE INSERT ON barangkeluar
            FOR EACH ROW
            BEGIN
                DECLARE tgl_masuk_pertama DATE;

                -- Mendapatkan tgl_masuk pertama dari tabel barangmasuk berdasarkan barang_id
                SET tgl_masuk_pertama = (SELECT MIN(tgl_masuk) FROM barangmasuk WHERE barang_id = NEW.barang_id);

                -- Memeriksa apakah tgl_keluar lebih awal dari tgl_masuk pertama
                IF NEW.tgl_keluar < tgl_masuk_pertama THEN
                    SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "Tanggal keluar tidak boleh lebih awal dari tanggal masuk pertama";
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS time_travel');
    }
}
