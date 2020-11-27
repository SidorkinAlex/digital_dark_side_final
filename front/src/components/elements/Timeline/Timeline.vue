<template>
  <!-- Здесь может быть ваш хардкод: -->
  <div class="timeline">
    <div class="events-wrapper">
      <div class="events" style="width: 1740px; transform: translateX(0px);">
        <ol>
          <li>
            <a
              href="#0"
              data-date="26/01/2019"
              class="selected"
              style="left: 120px;"
              >18 окт</a
            >
          </li>
          <li>
            <a href="#0" data-date="28/02/2019" style="left: 240px;">19 окт</a>
          </li>
          <li>
            <a href="#0" data-date="20/04/2019" style="left: 420px;">20 окт</a>
          </li>
          <li>
            <a href="#0" data-date="20/05/2019" style="left: 540px;">21 окт</a>
          </li>
          <li>
            <a href="#0" data-date="09/07/2019" style="left: 720px;">22 окт</a>
          </li>
          <li>
            <a href="#0" data-date="30/08/2019" style="left: 960px;">23 окт</a>
          </li>
          <li>
            <a href="#0" data-date="15/09/2019" style="left: 1020px;">24 окт</a>
          </li>
          <li>
            <a href="#0" data-date="01/11/2019" style="left: 1140px;">25 окт</a>
          </li>
          <li>
            <a href="#0" data-date="10/12/2019" style="left: 1320px;">26 окт</a>
          </li>
          <li>
            <a href="#0" data-date="19/01/2020" style="left: 1440px;">27 окт</a>
          </li>
          <li>
            <a href="#0" data-date="03/03/2020" style="left: 1620px;">28 окт</a>
          </li>
        </ol>

        <span
          class="filling-line"
          aria-hidden="true"
          style="transform: scaleX(0.0823905);"
        ></span>
      </div>
    </div>
    <ul class="cd-timeline-navigation">
      <li><a href="#0" class="prev inactive">Prev</a></li>
      <li><a href="#0" class="next">Next</a></li>
    </ul>
  </div>
</template>
<script>
export default {
  props: {}
}
</script>
