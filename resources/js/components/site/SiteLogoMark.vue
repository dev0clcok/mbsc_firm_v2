<template>
    <!-- The firm's mark: a courthouse with scales and a tie. Drawn as vectors so it stays sharp at any size. -->
    <svg viewBox="0 0 78 72" fill="none" aria-hidden="true">
        <g fill="currentColor">
            <!-- steps and beam -->
            <rect x="17" y="62" width="44" height="2.4" rx="0.4" />
            <rect x="14" y="66.2" width="50" height="2.4" rx="0.4" />
            <rect x="7" y="29.6" width="64" height="2.6" rx="0.4" />
            <!-- columns with capitals and bases -->
            <g v-for="x in [22.5, 31, 47, 55.5]" :key="x">
                <rect :x="x - 3.2" y="24.8" width="6.4" height="2.2" rx="0.3" />
                <rect :x="x - 1.1" y="27" width="2.2" height="2.6" />
                <rect :x="x - 1.1" y="32.2" width="2.2" height="26" />
                <rect :x="x - 3.2" y="58.2" width="6.4" height="2.2" rx="0.3" />
            </g>
            <!-- scale pans -->
            <rect x="2" y="48.4" width="17" height="2.2" rx="0.3" />
            <rect x="59" y="48.4" width="17" height="2.2" rx="0.3" />
        </g>
        <g stroke="currentColor" stroke-width="2.3" stroke-linejoin="round" stroke-linecap="round">
            <!-- pediment -->
            <path d="M17.5 22.4 39 4.2l21.5 18.2z" />
            <circle cx="39" cy="15.2" r="2.5" stroke-width="1.9" />
            <!-- scales -->
            <path d="M10.5 32.6 3.4 49m7.1-16.4L17.6 49M3.2 50.4a7.4 7.4 0 0 0 14.6 0" />
            <path d="M67.5 32.6 60.4 49m7.1-16.4L74.6 49m-14.4 1.4a7.4 7.4 0 0 0 14.6 0" />
        </g>
        <!-- tie -->
        <path fill="#e82068" d="M37.2 24.6h3.6v3.1a4 4 0 0 1 .9 6.3l2.3 20.6-5 6.8-5-6.8L36.300 34a4 4 0 0 1 .9-6.3z" />
        <circle cx="39" cy="30.9" r="1.5" fill="#fff" />
    </svg>
</template>
