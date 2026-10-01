<?php

use App\Models\AuditLog;
use App\Models\Service;
use App\Models\User;

beforeEach(function () {
    // Record changes are not logged from the console by default.
    config(['audit.console' => true]);
    $this->admin = User::factory()->create(['email' => config('admin.super_admin_email')]);
});

test('a change to a record is logged once, with the request that made it', function () {
    $service = Service::create(['slug' => 'vat', 'title' => 'VAT Advisory', 'is_active' => true]);
    AuditLog::query()->delete();

    $this->actingAs($this->admin)->patch("/admin/services/{$service->id}/toggle")->assertRedirect();

    $log = AuditLog::sole();

    expect($log->event)->toBe('model.updated')
        ->and($log->method)->toBe('PATCH')
        ->and($log->route)->toBe('admin.list.toggle')
        ->and($log->status_code)->toBe(302)
        ->and($log->user_id)->toBe($this->admin->id)
        ->and(array_keys($log->new_values))->toContain('is_active');
});

test('a request that changes no record is still logged', function () {
    $this->actingAs($this->admin)->post('/admin/faqs/reorder', ['ids' => [1, 2]])->assertRedirect();

    $log = AuditLog::sole();

    expect($log->event)->toBe('request')->and($log->route)->toBe('admin.list.reorder');
});

test('the audit log names the record and the fields that changed', function () {
    $service = Service::create(['slug' => 'vat', 'title' => 'VAT Advisory', 'is_active' => true]);
    $this->actingAs($this->admin)->patch("/admin/services/{$service->id}/toggle");

    $gone = Service::create(['slug' => 'old', 'title' => 'Old service']);
    $this->delete("/admin/services/{$gone->id}");

    $this->get('/admin/audit-logs?event=model.updated')->assertOk()->assertInertia(fn ($page) => $page
        ->where('auditLogs.data.0.subject.type', 'Service')
        ->where('auditLogs.data.0.subject.name', 'VAT Advisory')
        ->where('auditLogs.data.0.subject.changes', ['is_active']));

    // A deleted record is named from the values kept in the log.
    $this->get('/admin/audit-logs?event=model.deleted')->assertInertia(fn ($page) => $page
        ->where('auditLogs.data.0.subject.name', 'Old service')
        ->where('auditLogs.data.0.subject.changes', []));
});
