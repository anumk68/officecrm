@extends('layouts.app')

@section('content')
    <style>
        .timer-container {
            background: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 16px 20px;
            border-radius: 10px;
            border: 1px solid #eaeaea;
            max-width: 100%;
            margin: 0;

        }

        .timer-input {
            border: none;
            background: transparent;
            font-size: 1.1rem;
            padding: 6px 0;
            width: 100%;
            color: #333;
        }

        .timer-input:focus {
            outline: none;
            box-shadow: none;
        }

        .timer-input::placeholder {
            color: #a0a0a0;
        }

        .project-btn {
            background: transparent;
            border: none;
            color: #007bff;
            padding: 6px 12px;
            font-size: 0.9rem;
            border-radius: 6px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
        }

        .project-btn:hover {
            background: #f0f7ff;
        }

        .tag-btn {
            background: transparent;
            border: 1px solid #e0e0e0;
            color: #666;
            padding: 6px 12px;
            font-size: 0.9rem;
            border-radius: 6px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
        }

        .tag-btn:hover {
            background: #f8f9fa;
        }

        .tracker-box {
            background: #fff;
            border: 1px solid #e3e3e3;
            padding: 8px 12px;
            border-radius: 6px;
            font-family: monospace;
            font-size: 1.1rem;
            min-width: 110px;
            text-align: center;
            color: #333;
        }

        .start-btn {
            background: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 8px 20px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .start-btn:hover {
            background: #0069d9;
        }

        .stop-btn {
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 8px 20px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .stop-btn:hover {
            background: #c82333;
        }

        .icon-btn {
            background: transparent;
            border: none;
            color: #666;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .icon-btn:hover {
            background: #f8f9fa;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 12px;
            width: 320px;
        }

        .dropdown-header {
            font-size: 0.8rem;
            font-weight: 600;
            color: #666;
            margin-bottom: 8px;
        }

        .project-item {
            padding: 8px 0;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .project-item:hover {
            background: #f8f9fa;
        }

        .project-name {
            font-weight: 500;
            color: #333;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .project-meta {
            font-size: 0.75rem;
            color: #666;
            background: #f1f1f1;
            padding: 2px 6px;
            border-radius: 10px;
        }

        .task-list {
            margin-left: 15px;
            margin-top: 8px;
            border-left: 2px solid #eaeaea;
            padding-left: 12px;
        }

        .task-item {
            padding: 6px 0;
            border-bottom: 1px solid #f5f5f5;
            font-size: 0.85rem;
            color: #555;
        }

        .task-item:last-child {
            border-bottom: none;
        }

        .task-time {
            color: #888;
            font-size: 0.8rem;
            margin-left: 8px;
        }

        .create-option {
            padding: 8px 0;
            color: #007bff;
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .create-option:hover {
            color: #0056b3;
        }

        .divider {
            height: 1px;
            background: #eaeaea;
            margin: 12px 0;
        }

        .mode-icon {
            color: #666;
            font-size: 1.1rem;
            margin-left: 12px;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .mode-icon:hover {
            color: #333;
        }

        .mode-icon.active {
            color: #007bff;
        }

        .project-search {
            border-radius: 6px;
            border: 1px solid #e0e0e0;
            padding: 8px 12px;
            font-size: 0.9rem;
            margin-bottom: 12px;
        }

        .project-search:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.1);
        }

        .dropdown-menu.show {
            display: block !important;
        }

        #overtime-alert {
            width: 40%;
            margin-left: 30%;
            text-align: center;
            padding: 10px;
            background: #ffe5e5;
            color: #b30000;
            border-radius: 6px;
            font-weight: bold;
            margin-top: 10px;
            display: none;

        }

        .cl-dropdown-menu {
            width: 520px !important;
            /* 🔥 Bigger Width */
            min-width: 520px !important;
            /* Prevent auto shrinking */
            max-width: 95vw;
            /* Responsive on small screens */
            max-height: 500px !important;
            border-radius: 12px;
            overflow: hidden;
            padding: 0 !important;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.18);
        }

        .cl-dropdown-menu {
            right: auto !important;
            left: 50% !important;
            transform: translateX(-50%) !important;
            max-width: 95vw !important;
        }

        .cl-scroll-area {
            max-height: 350px;
            overflow-y: auto;
        }

        /* Custom search field */
        .cl-search-box input {
            width: 100%;
            border: 1px solid #ddd;
            padding: 8px 10px;
            border-radius: 6px;
            margin: 10px 0;

        }

        .cl-search-box input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.1);
        }

        .cl-search-box {
            padding-left: 10px !important;
            padding-right: 10px !important;
        }



        /* Project Item */
        .cl-dropdown-item {
            padding: 8px 4px;
            border-bottom: 1px solid #f5f5f5;
        }

        .cl-dropdown-item:hover {
            background: #fafafa;
        }

        /* Color dot */
        .cl-color-dot {
            width: 10px;
            height: 10px;
            background: #007bff;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
        }

        /* Task wrapper */

        .task-wrapper {
            display: none;
            transition: all 0.25s ease-in-out;
            padding-left: 12px;
        }

        .task-wrapper.show {
            opacity: 1;
        }

        .task-wrapper.hide {
            opacity: 0;
        }

        .task-item {
            padding: 8px 10px;
            border-radius: 6px;
            margin-bottom: 4px;
            background: #f9f9f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .task-item:hover {
            background: #f1f6ff;
        }


        /* Task eye icon */
        .task-view {
            color: #007bff;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
        }

        .task-view:hover {
            background: #eef4ff;
        }

        /* See more/less */
        .see-more {
            padding: 8px;
            text-align: center;
            cursor: pointer;
            font-weight: 600;
            color: #007bff;
        }

        .cl-dropdown-header {
            padding: 10px;
            font-family: fangsong;
        }

        .cl-project-name {
            margin-left: 10px;
            font-family: auto;
        }

        .text-muted {
            font-family: auto;
            font-size: 13px;
        }

        /* Center dropdown below button + right side से gap */
        .dropdown-menu.cl-dropdown-menu {
            left: 50% !important;
            transform: translateX(-50%) !important;
            margin-right: 20px !important;
        }

        /* Responsive width */
        @media (max-width: 768px) {
            .cl-dropdown-menu {
                width: 400px !important;
                min-width: auto !important;
                left: 10px !important;
                right: 10px !important;
                transform: translateX(-50%) !important;
            }
        }
    </style>


    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="timer-container d-flex justify-content-center">

                    <div class="d-flex align-items-center gap-3" style="flex: 1;">
                        <input type="text" class="timer-input" id="task-input" placeholder="What are you working on?"
                            readonly>
                        <div class="dropdown">
                            <button class="btn project-btn d-flex align-items-center dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" data-bs-auto-close="false">
                                <i class="fa-solid fa-circle-plus me-2"></i> Project/Tasks
                            </button>

                            <div class="dropdown-menu cl-dropdown-menu p-2" data-bs-auto-close="false">

                                <div class="cl-search-box">
                                    <input type="text" id="projectSearch" placeholder="Search Project or Client">
                                </div>

                                <div class="cl-dropdown-header">
                                    {{ count($projects) }} Projects
                                </div>

                                <div class="cl-scroll-area" id="projectList">

                                    @foreach ($projects as $project)
                                        <div class="cl-dropdown-item project-row">

                                            <div class="d-flex justify-content-between ">
                                                <div class="cl-project-name toggle-task ">
                                                    <span class="cl-color-dot"
                                                        style="background: {{ $project->color ?? '#007bff' }};"></span>
                                                    <span>{{ $project->project_name }}</span>
                                                </div>

                                                <div class="text-muted ">
                                                    {{ count($project->tasks) }} Tasks
                                                    {{-- <i class="fa-solid fa-chevron-down ms-2 toggle-task"></i> --}}
                                                    <i class="fa-solid fa-angle-down ms-2 toggle-task "></i>

                                                </div>
                                            </div>

                                            <div class="task-wrapper" style="display:none;">
                                                @foreach ($project->tasks as $task)
                                                    <div class="task-item" data-task='{!! json_encode([
                                                        'id' => $task->id,
                                                        'title' => $task->title,
                                                        'deadline' => $task->deadline ?? '00:00:00',
                                                        'spent_seconds' => $task->spent_seconds ?? 0,
                                                    ]) !!}'>

                                                        <b> {{ $task->title }}</b>

                                                        <div class="d-flex align-items-center gap-2 ms-auto">
                                                            <small class="text-muted">
                                                                {{ \Carbon\Carbon::parse($task->created_at)->diffForHumans() }}
                                                            </small>
                                                            <i class="fa-solid fa-eye task-view"
                                                                data-task-id="{{ $task->id }}"
                                                                style="cursor:pointer; color:#007bff;"></i>
                                                        </div>

                                                    </div>
                                                @endforeach
                                            </div>

                                        </div>
                                    @endforeach

                                </div>

                            </div>

                        </div>


                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <button class="btn tag-btn d-flex align-items-center">
                            <i class="fa-solid fa-tag me-2"></i>
                            <span>Tags</span>
                        </button>

                        <div class="tracker-box" id="timer-display">00:00:00</div>

                        <button class="btn start-btn" id="timer-button" disabled>Start</button>

                        {{-- <div class="dropdown">
                    <button class="btn icon-btn" data-bs-toggle="dropdown"><i
                            class="fa-solid fa-ellipsis-vertical"></i></button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#"><i class="fa-solid fa-pencil me-2"></i> Edit</a></li>
                    </ul> --}}
                        {{-- </div> --}}

                        <i class="fa-solid fa-clock mode-icon active" id="timer-mode"></i>
                        {{-- <i class="fa-solid fa-list mode-icon" id="manual-mode"></i> --}}
                    </div>
                </div>

                <div class="mx-center" id="overtime-alert">
                    <strong>Task time completed.</strong> Extra time running now.
                </div>
            </div>
            <div id="task-log-container " class="container-fluid card mt-4 pt-4">

                <div class="card-header bg-primary text-white">
                    <strong>Task Time Logs</strong>
                </div>

                <div class="d-flex justify-content-between mb-3 mt-3 px-3">
                    {{-- <div class="d-flex gap-2">
                <input type="date" id="filter_from" class="form-control" style="width:170px">
                <input type="date" id="filter_to" class="form-control" style="width:170px">
                <button class="btn btn-primary" id="applyFilter">Filter</button>
            </div> --}}

                    <div style="width: 250px;">
                        {{-- <input type="text" id="searchLogs" class="form-control" placeholder="Search logs..."> --}}
                    </div>
                </div>

                <div class=" pb-4" id="task-log-table">
                    @include('partials.task_logs_table')
                </div>
            </div>
        </div>
    </div>

    <!-- Task Details Modal -->
    <div class="modal fade" id="taskDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Task Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div id="task-details-loader" class="text-center py-4">
                        <div class="spinner-border"></div>
                    </div>

                    <div id="task-details-content" style="display:none;">
                        <h4 id="t_title"></h4>
                        {{-- <p id="t_description"></p> --}}

                        <table class="table table-bordered mt-3">
                            <tr>
                                <th width="30%">Project</th>
                                <td id="t_project"></td>
                            </tr>
                            <tr>
                                <th width="30%">Assign By</th>
                                <td id="t_creator"></td>
                            </tr>

                            <tr>
                                <th>Status</th>
                                <td id="t_status"></td>
                            </tr>

                            <tr>
                                <th>Deadline</th>
                                <td id="t_deadline"></td>
                            </tr>

                            <tr>
                                <th>Assigned Date</th>
                                <td id="t_assigned_date"></td>
                            </tr>

                            <tr>
                                <th>Description</th>
                                <td id="t_description"></td>
                            </tr>
                        </table>
                        <div class="row">
                            <div class="view d-flex justify-content-end align-items-center gap-3">
                                <p class="mt-2">If your task is done and then click on done.</p>
                                <button id="mark-task-done" class="submit btn btn-primary d-flex">Done
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        /* -----------------------------------------
                           GLOBAL VARIABLES (Backend Controlled)
                        ----------------------------------------- */
        let selectedTask = null;
        let secondsLeft = 0;
        let interval = null;
        let isRunning = false;
        let currentLogId = null;
        let startRemainingSeconds = 0;

        /* -----------------------------------------
          TIME FORMATTER
        ----------------------------------------- */
        function formatTime(seconds) {
            const neg = seconds < 0;
            const s = Math.abs(seconds);
            const h = String(Math.floor(s / 3600)).padStart(2, '0');
            const m = String(Math.floor((s % 3600) / 60)).padStart(2, '0');
            const sec = String(s % 60).padStart(2, '0');
            return (neg ? "-" : "") + `${h}:${m}:${sec}`;
        }

        /* -----------------------------------------
           UPDATE DISPLAY
        ----------------------------------------- */
        function updateTimerDisplay() {
            let display = document.getElementById("timer-display");
            if (!display) return;

            display.textContent = formatTime(secondsLeft);

            // UI alert box visible or hidden
            let alertBox = document.getElementById("overtime-alert");
            alertBox.style.display = secondsLeft < 0 ? "block" : "none";
        }


        /* -----------------------------------------
          TIMER START FUNCTION
        ----------------------------------------- */
        let oneMinuteAlertShown = false;
        let overtimeAlertShown = false;

        // 🎵 Sound
        // const alertSound = new Audio("https://assets.mixkit.co/sfx/preview/mixkit-warning-alarm-buzzer-991.mp3");

        function startInterval() {
            if (interval) clearInterval(interval);

            interval = setInterval(() => {
                secondsLeft--;
                updateTimerDisplay();

                /* -----------------------------------------
                  1️⃣  Show alert 1 minute before time ends
                ----------------------------------------- */
                if (secondsLeft === 60 && !oneMinuteAlertShown) {
                    oneMinuteAlertShown = true;
                    alert("⚠️ Only 1 minute remaining!");
                    // alertSound.play();
                }

                /* -----------------------------------------
                  2️⃣  Show alert when extra-time starts 
                      (first negative second)
                ----------------------------------------- */
                if (secondsLeft === -1 && !overtimeAlertShown) {
                    overtimeAlertShown = true;
                    alert("⏳ Extra time started!");
                    // alertSound.play();
                    document.getElementById("overtime-alert").style.display = "block";
                }

            }, 1000);
        }


        /* -----------------------------------------
          FETCH ACTIVE TASK (BACKEND SYNC)
        ----------------------------------------- */
        function fetchActiveTaskFromServer() {
            fetch("{{ route('task.active') }}")
                .then(r => r.json())
                .then(data => {

                    // -------------------------
                    // AUTO STOP if backend stopped
                    // -------------------------
                    if (!data.running && isRunning) {

                        if (interval) clearInterval(interval);
                        interval = null;

                        isRunning = false;
                        selectedTask = null;
                        currentLogId = null;
                        secondsLeft = 0;

                        document.getElementById("task-input").value = "";
                        document.getElementById("timer-display").textContent = "00:00:00";

                        const btn = document.getElementById("timer-button");
                        btn.textContent = "Start";
                        btn.classList.add("start-btn");
                        btn.classList.remove("stop-btn");
                        btn.disabled = true;

                        return;
                    }

                    // If nothing running → exit
                    if (!data.running) return;

                    // -------------------------
                    // LOAD RUNNING TASK
                    // -------------------------
                    selectedTask = data.task;
                    currentLogId = data.log_id;
                    startRemainingSeconds = data.start_remaining_seconds;

                    document.getElementById("task-input").value = selectedTask.title;

                    const btn = document.getElementById("timer-button");
                    btn.disabled = false;
                    btn.textContent = "Stop";
                    btn.classList.remove("start-btn");
                    btn.classList.add("stop-btn");

                    let startedAt = new Date(data.started_at);
                    let now = new Date();
                    let diffSec = Math.floor((now - startedAt) / 1000);

                    secondsLeft = startRemainingSeconds - diffSec;

                    updateTimerDisplay();

                    if (!interval) startInterval();

                    isRunning = true;
                });
        }


        /* -----------------------------------------
          PAGE LOAD → GET ACTIVE TASK
        ----------------------------------------- */
        window.addEventListener("load", function() {
            fetchActiveTaskFromServer();
        });

        /* -----------------------------------------
          AUTO POLL EVERY 10 SEC
        ----------------------------------------- */
        setInterval(fetchActiveTaskFromServer, 10000);


        /* -----------------------------------------
          DOM READY
        ----------------------------------------- */
        document.addEventListener('DOMContentLoaded', function() {

            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const timerButton = document.getElementById('timer-button');
            const taskInput = document.getElementById('task-input');
            const projectSearch = document.getElementById('projectSearch');

            /* --------------------------------------------------------------------
                TASK SELECT FROM DROPDOWN
            -------------------------------------------------------------------- */
            function parseDurationToSeconds(hhmmss) {
                const p = hhmmss.split(':').map(x => parseInt(x) || 0);
                return p[0] * 3600 + p[1] * 60 + p[2];
            }

            document.querySelectorAll('.task-item').forEach(el => {
                el.addEventListener('click', function() {


                    if (isRunning) {
                        alert("A task is already running. Please stop it first.");
                        return;
                    }

                    document.querySelector('.project-btn').click();

                    const t = JSON.parse(this.getAttribute('data-task'));
                    selectedTask = t;

                    taskInput.value = t.title;

                    let deadlineSec = parseDurationToSeconds(t.deadline || "00:00:00");
                    let spent = t.spent_seconds || 0;

                    startRemainingSeconds = deadlineSec + spent;
                    secondsLeft = startRemainingSeconds;

                    updateTimerDisplay();
                    timerButton.disabled = false;
                });
            });

            /* --------------------------------------------------------------------
                DROPDOWN PROJECT SEARCH
            -------------------------------------------------------------------- */
            projectSearch.addEventListener('keyup', function() {
                const val = this.value.toLowerCase();
                document.querySelectorAll('.project-row').forEach(row => {
                    row.style.display = row.innerText.toLowerCase().includes(val) ? '' : 'none';
                });
            });

            /* --------------------------------------------------------------------
                DROPDOWN EXPAND/COLLAPSE
            -------------------------------------------------------------------- */
            document.querySelectorAll('.toggle-task').forEach(row => {
                row.addEventListener('click', function() {

                    let wrapper = this.closest('.cl-dropdown-item').querySelector('.task-wrapper');
                    let icon = this.querySelector('.arrow-icon');

                    // Toggle
                    wrapper.style.display = wrapper.style.display === 'block' ? 'none' : 'block';

                    if (icon) {
                        icon.classList.toggle('fa-angle-down');
                        icon.classList.toggle('fa-angle-up');
                    }
                });
            });

            /* --------------------------------------------------------------------
                START / STOP TIMER
            -------------------------------------------------------------------- */
            timerButton.addEventListener('click', function() {

                // START
                if (!isRunning) {

                    if (!selectedTask) {
                        alert("Please select a task first.");
                        return;
                    }

                    fetch("{{ route('task.start') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": csrf
                            },
                            body: JSON.stringify({
                                task_id: selectedTask.id,
                                start_remaining_seconds: startRemainingSeconds
                            })
                        })
                        .then(r => r.json())
                        .then(data => {

                            if (data.status !== "ok") {
                                alert("Cannot start timer.");
                                return;
                            }

                            currentLogId = data.log_id;
                            isRunning = true;
                            oneMinuteAlertShown = false;
                            overtimeAlertShown = false;

                            timerButton.textContent = "Stop";
                            timerButton.classList.remove("start-btn");
                            timerButton.classList.add("stop-btn");

                            startInterval();
                        });

                    return;
                }

                // STOP
                fetch("{{ route('task.stop') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": csrf
                        },
                        body: JSON.stringify({
                            log_id: currentLogId
                        })
                    })
                    .then(r => r.json())
                    .then(data => {

                        if (data.status !== "ok") {
                            alert("Task deleted. Timer reset.");
                            location.reload();
                            return;
                        }

                        clearInterval(interval);
                        interval = null;
                        isRunning = false;
                        oneMinuteAlertShown = false;
                        overtimeAlertShown = false;
                        timerButton.textContent = "Start";
                        timerButton.classList.add("start-btn");
                        timerButton.classList.remove("stop-btn");

                        if (data.is_overtime) {
                            alert("Task overtime: " + formatTime(data.overtime_seconds));
                        } else {
                            alert("Duration: " + formatTime(data.duration_seconds));
                        }

                        selectedTask = null;
                        currentLogId = null;
                        secondsLeft = 0;

                        taskInput.value = "";
                        timerButton.disabled = true;
                        document.getElementById("timer-display").textContent = "00:00:00";


                        setTimeout(() => location.reload(), 300);
                    });

            });

        });
    </script>


    <script>
        function formatDeadlineHM(value) {
            if (!value) return "-";

            // Case: Only HH:MM:SS
            if (/^\d{2}:\d{2}:\d{2}$/.test(value)) {
                let parts = value.split(":");
                let h = parts[0];
                let m = parts[1];
                return `${h}h : ${m}m`;
            }

            // Case: Full Datetime (YYYY-MM-DD HH:MM:SS)
            let d = new Date(value);
            if (isNaN(d.getTime())) return "-";

            let h = String(d.getHours()).padStart(2, "0");
            let m = String(d.getMinutes()).padStart(2, "0");

            return `${h}h : ${m}m`;
        }

        function formatAssignDate(dateString) {
            if (!dateString) return "-";

            let d = new Date(dateString);
            if (isNaN(d.getTime())) return "-";

            // Date Format
            let day = String(d.getDate()).padStart(2, "0");
            let month = String(d.getMonth() + 1).padStart(2, "0");
            let year = d.getFullYear();

            // Time Format (12h format)
            let hours = d.getHours();
            let minutes = String(d.getMinutes()).padStart(2, "0");
            let ampm = hours >= 12 ? "PM" : "AM";

            hours = hours % 12;
            hours = hours ? hours : 12; // 0 → 12

            return `${day}-${month}-${year}  ${hours}:${minutes} ${ampm}`;
        }


        function cleanPosition(str) {
            if (!str) return "";

            // Remove parentheses first
            str = str.replace(/[()]/g, "");

            // Replace multiple underscores with space
            str = str.replace(/_/g, " ");

            // Split by space and capitalize each word
            return str
                .split(" ")
                .map(w => w.charAt(0).toUpperCase() + w.slice(1))
                .join(" ");
        }

        document.querySelectorAll('.task-view').forEach(icon => {
            icon.addEventListener('click', function(e) {
                e.stopPropagation();

                let taskId = this.getAttribute('data-task-id');
                let modal = new bootstrap.Modal(document.getElementById('taskDetailsModal'));
                modal.show();

                document.getElementById('task-details-loader').style.display = 'block';
                document.getElementById('task-details-content').style.display = 'none';

                fetch("{{ url('task/details') }}/" + taskId)
                    .then(r => r.json())
                    .then(data => {

                        if (data.status !== "ok") {
                            alert("Task not found");
                            return;
                        }
                        document.getElementById('mark-task-done').setAttribute('data-id', taskId);
                        let t = data.task;
                        let creatorName = t.creator?.full_name ?? "-";
                        let rawPosition = t.creator?.position ?? "";
                        let cleanPos = cleanPosition(rawPosition);

                        document.getElementById('t_title').innerText = t.title;
                        document.getElementById('t_creator').innerText = cleanPos ?
                            `${creatorName} (${cleanPos})` :
                            creatorName;

                        document.getElementById('t_description').innerText = t.description ?? "-";
                        document.getElementById('t_project').innerText = t.project?.project_name ?? "-";
                        document.getElementById('t_status').innerText = t.status ?? "-";
                        document.getElementById('t_deadline').innerText = formatDeadlineHM(t.deadline);
                        document.getElementById('t_assigned_date').innerText = formatAssignDate(t
                            .created_at);

                        document.getElementById('task-details-loader').style.display = 'none';
                        document.getElementById('task-details-content').style.display = 'block';
                    });
            });
        });


        document.getElementById('mark-task-done').addEventListener('click', function() {

            let taskId = this.getAttribute('data-id');
            let csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch("{{ route('task.update.status') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrf,
                    },
                    body: JSON.stringify({
                        task_id: taskId
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.status === "success") {

                        Swal.fire({
                            icon: 'success',
                            title: 'Task Completed!',
                            text: 'Your task has been successfully marked as done.',
                            confirmButtonColor: '#3085d6',
                        }).then(() => {
                            location.reload();
                        });

                    } else {

                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong.',
                        });

                    }
                });

        });
    </script>
@endsection
