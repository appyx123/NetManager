<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Standard FreeRADIUS schema tables for MySQL / SQLite.
     */
    public function up(): void
    {
        // 1. radcheck: Check attributes for authentication (e.g. Cleartext-Password, Calling-Station-Id)
        if (!Schema::hasTable('radcheck')) {
            Schema::create('radcheck', function (Blueprint $table) {
                $table->increments('id');
                $table->string('username', 64)->default('');
                $table->string('attribute', 64)->default('');
                $table->string('op', 2)->default('==');
                $table->string('value', 253)->default('');
                $table->index('username');
            });
        }

        // 2. radreply: Reply attributes sent back to NAS (e.g. Mikrotik-Rate-Limit, Mikrotik-Address-List)
        if (!Schema::hasTable('radreply')) {
            Schema::create('radreply', function (Blueprint $table) {
                $table->increments('id');
                $table->string('username', 64)->default('');
                $table->string('attribute', 64)->default('');
                $table->string('op', 2)->default('=');
                $table->string('value', 253)->default('');
                $table->index('username');
            });
        }

        // 3. radacct: Accounting sessions logged by NAS
        if (!Schema::hasTable('radacct')) {
            Schema::create('radacct', function (Blueprint $table) {
                $table->bigIncrements('radacctid');
                $table->string('acctsessionid', 64)->default('');
                $table->string('acctuniqueid', 32)->default('')->unique();
                $table->string('username', 64)->default('');
                $table->string('realm', 64)->nullable()->default('');
                $table->string('nasipaddress', 45)->default('');
                $table->string('nasportid', 32)->nullable();
                $table->string('nasporttype', 32)->nullable();
                $table->dateTime('acctstarttime')->nullable();
                $table->dateTime('acctupdatetime')->nullable();
                $table->dateTime('acctstoptime')->nullable();
                $table->integer('acctinterval')->nullable();
                $table->unsignedInteger('acctsessiontime')->nullable();
                $table->string('acctauthentic', 32)->nullable();
                $table->string('connectinfo_start', 50)->nullable();
                $table->string('connectinfo_stop', 50)->nullable();
                $table->bigInteger('acctinputoctets')->nullable();
                $table->bigInteger('acctoutputoctets')->nullable();
                $table->string('calledstationid', 50)->default('');
                $table->string('callingstationid', 50)->default('');
                $table->string('acctterminatecause', 32)->default('');
                $table->string('servicetype', 32)->nullable();
                $table->string('framedprotocol', 32)->nullable();
                $table->string('framedipaddress', 45)->default('');

                $table->index('username');
                $table->index('framedipaddress');
                $table->index('acctsessionid');
                $table->index('acctsessiontime');
                $table->index('acctstarttime');
                $table->index('acctinterval');
                $table->index('acctstoptime');
                $table->index('nasipaddress');
            });
        }

        // 4. nas: Network Access Server (MikroTik Router RADIUS Clients)
        if (!Schema::hasTable('nas')) {
            Schema::create('nas', function (Blueprint $table) {
                $table->increments('id');
                $table->string('nasname', 128);
                $table->string('shortname', 32)->nullable();
                $table->string('type', 30)->default('other');
                $table->integer('ports')->nullable();
                $table->string('secret', 60)->default('secret');
                $table->string('server', 64)->nullable();
                $table->string('community', 50)->nullable();
                $table->string('description', 200)->default('RADIUS Client');
                $table->index('nasname');
            });
        }

        // 5. radusergroup: User to group mapping
        if (!Schema::hasTable('radusergroup')) {
            Schema::create('radusergroup', function (Blueprint $table) {
                $table->increments('id');
                $table->string('username', 64)->default('');
                $table->string('groupname', 64)->default('');
                $table->integer('priority')->default(1);
                $table->index('username');
            });
        }

        // 6. radgroupcheck: Group check attributes
        if (!Schema::hasTable('radgroupcheck')) {
            Schema::create('radgroupcheck', function (Blueprint $table) {
                $table->increments('id');
                $table->string('groupname', 64)->default('');
                $table->string('attribute', 64)->default('');
                $table->string('op', 2)->default('==');
                $table->string('value', 253)->default('');
                $table->index('groupname');
            });
        }

        // 7. radgroupreply: Group reply attributes
        if (!Schema::hasTable('radgroupreply')) {
            Schema::create('radgroupreply', function (Blueprint $table) {
                $table->increments('id');
                $table->string('groupname', 64)->default('');
                $table->string('attribute', 64)->default('');
                $table->string('op', 2)->default('=');
                $table->string('value', 253)->default('');
                $table->index('groupname');
            });
        }

        // 8. radpostauth: Log hasil autentikasi (Access-Accept / Access-Reject)
        if (!Schema::hasTable('radpostauth')) {
            Schema::create('radpostauth', function (Blueprint $table) {
                $table->id();
                $table->string('username', 64)->default('');
                $table->string('pass', 64)->default('');
                $table->string('reply', 32)->default(''); // 'Access-Accept' atau 'Access-Reject'
                $table->timestamp('authdate')->useCurrent();
                $table->index('username');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radpostauth');
        Schema::dropIfExists('radgroupreply');
        Schema::dropIfExists('radgroupcheck');
        Schema::dropIfExists('radusergroup');
        Schema::dropIfExists('nas');
        Schema::dropIfExists('radacct');
        Schema::dropIfExists('radreply');
        Schema::dropIfExists('radcheck');
    }
};
