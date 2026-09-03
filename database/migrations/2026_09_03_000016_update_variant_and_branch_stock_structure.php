<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('product_variants') && Schema::hasTable('produk_varian')) {
            throw new RuntimeException(
                'Tabel product_variants dan produk_varian sama-sama tersedia. Satukan data sebelum migration dilanjutkan.'
            );
        }

        if (Schema::hasTable('product_variants')) {
            Schema::rename('product_variants', 'produk_varian');
        }

        if (Schema::hasTable('produk_varian')) {
            if (! Schema::hasColumn('produk_varian', 'image')) {
                Schema::table('produk_varian', function (Blueprint $table) {
                    $table->string('image')->nullable()->after('harga_jual');
                });
            }

            if (! Schema::hasColumn('produk_varian', 'satuan')) {
                Schema::table('produk_varian', function (Blueprint $table) {
                    $table->string('satuan', 50)->default('pcs')->after('image');
                });
            }

            if (Schema::hasColumn('produk_varian', 'stok')) {
                Schema::table('produk_varian', function (Blueprint $table) {
                    $table->dropColumn('stok');
                });
            }
        }

        if (Schema::hasTable('branch_stocks') && Schema::hasTable('stok_cabang')) {
            throw new RuntimeException(
                'Tabel branch_stocks dan stok_cabang sama-sama tersedia. Satukan data sebelum migration dilanjutkan.'
            );
        }

        if (Schema::hasTable('branch_stocks')) {
            Schema::rename('branch_stocks', 'stok_cabang');
        }

        if (! Schema::hasTable('stok_cabang')) {
            return;
        }

        if (Schema::hasColumn('stok_cabang', 'stok_minimum')) {
            Schema::table('stok_cabang', function (Blueprint $table) {
                $table->dropColumn('stok_minimum');
            });
        }

        if (! Schema::hasIndex('stok_cabang', ['cabang_id', 'varian_id'], 'unique')) {
            Schema::table('stok_cabang', function (Blueprint $table) {
                $table->unique(
                    ['cabang_id', 'varian_id'],
                    'stok_cabang_cabang_id_varian_id_unique'
                );
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('stok_cabang')) {
            if (! Schema::hasColumn('stok_cabang', 'stok_minimum')) {
                Schema::table('stok_cabang', function (Blueprint $table) {
                    $table->unsignedInteger('stok_minimum')->default(0)->after('stok');
                });
            }

            if (! Schema::hasTable('branch_stocks')) {
                Schema::rename('stok_cabang', 'branch_stocks');
            }
        }

        if (Schema::hasTable('produk_varian')) {
            $columns = array_values(array_filter(
                ['satuan', 'image'],
                fn (string $column): bool => Schema::hasColumn('produk_varian', $column)
            ));

            if ($columns !== []) {
                Schema::table('produk_varian', function (Blueprint $table) use ($columns) {
                    $table->dropColumn($columns);
                });
            }

            if (! Schema::hasTable('product_variants')) {
                Schema::rename('produk_varian', 'product_variants');
            }
        }
    }
};
