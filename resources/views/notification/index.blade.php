@extends('layouts.app')

@section('title', 'All Notifications')

@section('content')
    <div style="padding-top:80px">
        <main class="main-content">
            <div class="row p-4">
                <div class="container">
                    <div class="email-header mb-3">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h4 class="mb-0"><i class="fa-solid fa-bell"></i> All Notifications</h4>
                                <p class="mb-0 opacity-75">View all system notifications and updates.</p>
                            </div>
                            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                <form method="GET" class="d-flex justify-content-md-end align-items-center gap-2">
                                    <input type="date" name="date" placeholder="Select date"
                                        value="{{ $selectedDate ?? '' }}" class="form-control form-control-sm w-auto">
                                    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                                    @if (request('date'))
                                        <a href="{{ route('notifications.index') }}"
                                            class="btn btn-secondary btn-sm">Reset</a>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid p-4 border shadow-sm rounded bg-white">
                        @if ($notifications->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach ($notifications as $n)
                                    @php
                                        // Default: unread
                                        $isRead = false;

                                        // Case 1: user-specific notification (user_id is not null)
                                        if (!is_null($n->user_id)) {
                                            $isRead = $n->is_read == 1;
                                        }
                                        // Case 2: role/global notification (user_id is null)
                                        else {
                                            $readBy = is_array($n->read_by)
                                                ? $n->read_by
                                                : json_decode($n->read_by, true);
                                            $readBy = $readBy ?? [];
                                            $isRead = in_array(auth()->id(), $readBy);
                                        }
                                    @endphp

                                    <a href="{{ $n->url ?? '#' }}"
                                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-start {{ $isRead ? 'text-muted' : '' }}">
                                        <div>
                                            <div class="fw-semibold">{{ $n->title }}</div>
                                            <small class="text-secondary d-block">{{ $n->message }}</small>
                                        </div>

                                        <div class="text-end">
                                            <span class="text-secondary small d-block">
                                                {{ $n->created_at->format('d M Y, h:i A') }}
                                            </span>
                                            @if ($isRead)
                                                <span class="badge bg-success mt-1">Read</span>
                                            @else
                                                <span class="badge bg-danger mt-1">Unread</span>
                                            @endif
                                        </div>
                                    </a>
                                @endforeach
                            </div>

                            <div class="mt-3 d-flex justify-content-end">
                                {{ $notifications->links('pagination::bootstrap-5') }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="mdi mdi-bell-off-outline fs-1 text-secondary"></i>
                                <h6 class="mt-2 text-muted">No notifications found on this selected date</h6>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </main>
    </div>
@endsection
