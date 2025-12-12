<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('siswas')) {
            Schema::table('siswas', function (Blueprint $table) {
                if (! Schema::hasIndex('siswas', 'siswas_nis_index')) {
                    $table->index('nis');
                }

                if (! Schema::hasIndex('siswas', 'siswas_nama_siswa_index')) {
                    $table->index('nama_siswa');
                }

                if (! Schema::hasIndex('siswas', 'siswas_kelas_id_index')) {
                    $table->index('kelas_id');
                }

                $hasQrIndex = Schema::hasIndex('siswas', 'siswas_qrcode_token_index');
                $hasQrUnique = Schema::hasIndex('siswas', 'siswas_qrcode_token_unique');

                if (! $hasQrIndex && ! $hasQrUnique) {
                    $table->index('qrcode_token');
                }
            });
        }

        if (Schema::hasTable('kehadirans')) {
            Schema::table('kehadirans', function (Blueprint $table) {
                if (! Schema::hasIndex('kehadirans', 'kehadirans_siswa_id_index')) {
                    $table->index('siswa_id');
                }

                if (! Schema::hasIndex('kehadirans', 'kehadirans_tanggal_index')) {
                    $table->index('tanggal');
                }

                if (! Schema::hasIndex('kehadirans', 'kehadirans_status_index')) {
                    $table->index('status');
                }
            });
        }

        if (Schema::hasTable('kelas')) {
            Schema::table('kelas', function (Blueprint $table) {
                if (! Schema::hasIndex('kelas', 'kelas_wali_kelas_id_index')) {
                    $table->index('wali_kelas_id');
                }

                if (! Schema::hasIndex('kelas', 'kelas_tahun_ajaran_id_index')) {
                    $table->index('tahun_ajaran_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('siswas')) {
            Schema::table('siswas', function (Blueprint $table) {
                if (Schema::hasIndex('siswas', 'siswas_nis_index')) {
                    $table->dropIndex(['nis']);
                }

                if (Schema::hasIndex('siswas', 'siswas_nama_siswa_index')) {
                    $table->dropIndex(['nama_siswa']);
                }

                if (Schema::hasIndex('siswas', 'siswas_kelas_id_index')) {
                    $table->dropIndex(['kelas_id']);
                }

                if (Schema::hasIndex('siswas', 'siswas_qrcode_token_index')) {
                    $table->dropIndex(['qrcode_token']);
                }
            });
        }

        if (Schema::hasTable('kehadirans')) {
            Schema::table('kehadirans', function (Blueprint $table) {
                if (Schema::hasIndex('kehadirans', 'kehadirans_siswa_id_index')) {
                    $table->dropIndex(['siswa_id']);
                }

                if (Schema::hasIndex('kehadirans', 'kehadirans_tanggal_index')) {
                    $table->dropIndex(['tanggal']);
                }

                if (Schema::hasIndex('kehadirans', 'kehadirans_status_index')) {
                    $table->dropIndex(['status']);
                }
            });
        }

        if (Schema::hasTable('kelas')) {
            Schema::table('kelas', function (Blueprint $table) {
                if (Schema::hasIndex('kelas', 'kelas_wali_kelas_id_index')) {
                    $table->dropIndex(['wali_kelas_id']);
                }

                if (Schema::hasIndex('kelas', 'kelas_tahun_ajaran_id_index')) {
                    $table->dropIndex(['tahun_ajaran_id']);
                }
            });
        }
    }
};
