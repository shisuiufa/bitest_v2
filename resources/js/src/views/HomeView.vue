<template>
    <div class="row my-3">
        <div class="col-12">
            <filter-nav @selectedFilter="item => {this.filter = item; getTests();}"></filter-nav>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3">
            <card-list :tests=this.tests></card-list>
        </div>
        <div class="col-12 text-center">
            <ui-button v-if="this.pagination && this.pagination.current_page < this.pagination.last_page"
                       class="load-more"
                       @click="getTests(this.pagination.current_page + 1)">
                <i class="bi bi-arrow-clockwise"></i>
                Загрузить ещё
            </ui-button>
        </div>
    </div>
</template>

<script>
import UiButton from "@/components/UI/UiButton.vue";
import filterNav from "@/components/FilterNav.vue";
import CardList from "@/components/CardList.vue";
import axios from "axios";

export default {
    name: "HomeView",
    components: {
        CardList,
        filterNav,
        UiButton
    },
    data() {
        return {
            tests: [],
            search: '',
            filter: null,
            pagination: null,
        }
    },
    mounted() {
        this.getTests();
    },
    methods: {
      getTests(page = 1){
          axios.get('/api/tests', {
              params: {
                  'page': page,
                  'per_page': 6,
                  'search': this.search,
                  'filter': this.filter,
              }
          }).then((res) => {
              this.pagination = res.data.meta;

              if (page === 1) {
                  this.tests = res.data.data;
              } else {
                  this.tests = this.tests.concat(res.data.data);
              }
          })
      }
    },
}
</script>

<style  lang="scss">
.load-more {
    color: var(--main-color);
    font-weight: 600;
    background-color: transparent;
    border: 1px solid var(--border-color);

    &:hover {
        color: #fff;
        background-color: var(--brand-color);
        border: 1px solid transparent;
    }
}
</style>
