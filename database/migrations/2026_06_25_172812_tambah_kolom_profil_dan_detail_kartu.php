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
        Schema::table('users', function (Blueprint $table) {
            $table->string('whatsapp_number')->nullable()->after('email');
            $table->text('address')->nullable()->after('whatsapp_number');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->string('buyer_name')->nullable()->after('user_id');
            $table->string('buyer_phone')->nullable()->after('buyer_name');
            $table->string('recipient_name')->nullable()->after('buyer_phone');
            $table->text('greeting_card_text')->nullable()->after('recipient_name');
            $table->integer('greeting_card_fee')->default(0)->after('greeting_card_text');
            $table->text('shipping_address')->nullable()->after('greeting_card_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['whatsapp_number', 'address']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'buyer_name', 'buyer_phone', 'recipient_name',
                'greeting_card_text', 'greeting_card_fee', 'shipping_address'
            ]);
        });
    }
};
