<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('budget_heads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('contractors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact');
            $table->timestamps();
        });

        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact');
            $table->timestamps();
        });

        Schema::create('budgeted_costs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_head_id')->constrained('budget_heads')->onDelete('cascade');
            $table->decimal('total_cost', 15, 2);
            $table->decimal('building_a', 15, 2)->nullable();
            $table->decimal('building_b', 15, 2)->nullable();
            $table->decimal('building_c', 15, 2)->nullable();
            $table->decimal('building_d', 15, 2)->nullable();
            $table->decimal('development', 15, 2)->nullable();
            $table->decimal('technical_overhead', 15, 2)->nullable();
            $table->decimal('other_expenses', 15, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_head_id')->constrained('budget_heads')->onDelete('cascade');
            $table->foreignId('contractor_id')->constrained('contractors')->onDelete('cascade');
            $table->string('work_order_no');
            $table->date('date');
            $table->decimal('amount_without_gst', 15, 2);
            $table->decimal('gst_amount', 15, 2);
            $table->decimal('total_amount', 15, 2);
            $table->decimal('remaining_budget', 15, 2);
            $table->timestamps();
        });

        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_head_id')->constrained('budget_heads')->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
            $table->string('purchase_order_no');
            $table->date('date');
            $table->decimal('amount_without_gst', 15, 2);
            $table->decimal('gst_amount', 15, 2);
            $table->decimal('total_amount', 15, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('work_orders');
        Schema::dropIfExists('budgeted_costs');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('contractors');
        Schema::dropIfExists('budget_heads');
    }
};
