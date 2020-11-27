<template>
  <svg
    aria-hidden="true"
    focusable="false"
    data-prefix="fad"
    :data-icon="`signal-alt-${level}`"
    role="img"
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 640 512"
    :class="
      `svg-inline--fa fa-signal fa-signal-alt-${level} fa-w-20 fa-fw fa-2x`
    "
  >
    <g v-if="level === '1'" class="fa-group">
      <path
        fill="currentColor"
        d="M416 128h-32a32 32 0 0 0-32 32v320a32 32 0 0 0 32 32h32a32 32 0 0 0 32-32V160a32 32 0 0 0-32-32zM256 256h-32a32 32 0 0 0-32 32v192a32 32 0 0 0 32 32h32a32 32 0 0 0 32-32V288a32 32 0 0 0-32-32z"
        class="fa-secondary"
      ></path>
      <path
        fill="currentColor"
        d="M128 416v64a32 32 0 0 1-32 32H64a32 32 0 0 1-32-32v-64a32 32 0 0 1 32-32h32a32 32 0 0 1 32 32z"
        class="fa-primary"
      ></path>
    </g>
    <g v-if="level === '2'" class="fa-group">
      <path
        fill="currentColor"
        d="M416 128h-32a32 32 0 0 0-32 32v320a32 32 0 0 0 32 32h32a32 32 0 0 0 32-32V160a32 32 0 0 0-32-32z"
        class="fa-secondary"
      ></path>
      <path
        fill="currentColor"
        d="M96 384H64a32 32 0 0 0-32 32v64a32 32 0 0 0 32 32h32a32 32 0 0 0 32-32v-64a32 32 0 0 0-32-32zm160-128h-32a32 32 0 0 0-32 32v192a32 32 0 0 0 32 32h32a32 32 0 0 0 32-32V288a32 32 0 0 0-32-32z"
        class="fa-primary"
      ></path>
    </g>
    <g v-if="level === '3'" class="fa-group">
      <path
        fill="currentColor"
        d="M96 384H64a32 32 0 0 0-32 32v64a32 32 0 0 0 32 32h32a32 32 0 0 0 32-32v-64a32 32 0 0 0-32-32zm160-128h-32a32 32 0 0 0-32 32v192a32 32 0 0 0 32 32h32a32 32 0 0 0 32-32V288a32 32 0 0 0-32-32zm160-128h-32a32 32 0 0 0-32 32v320a32 32 0 0 0 32 32h32a32 32 0 0 0 32-32V160a32 32 0 0 0-32-32z"
        class="fa-primary"
      ></path>
    </g>
  </svg>
</template>

<script>
export default {
  props: {
    level: {
      type: String,
      default: '1'
    }
  }
};
</script>
