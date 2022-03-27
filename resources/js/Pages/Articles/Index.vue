<template>
  <div>
    Burasi Resoureces/JS/Pages/Articles/Index
    <hr />
    <ul>
      <li v-for="article in articles" :key="article.id">
        {{ article.title }}
      </li>
    </ul>
  </div>
</template>


<script>
export default {
  props: ["articles"],
};
</script>