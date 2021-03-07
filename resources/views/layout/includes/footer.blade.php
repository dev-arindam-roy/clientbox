<script src="{{ asset('public/assets/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('public/assets/bootstrap/popper.min.js') }}"></script>
<script src="{{ asset('public/assets/bootstrap/bootstrap.min.js') }}"></script>

<script src="{{ asset('public/assets/vue/vue.min.js') }}"></script>
<script src="{{ asset('public/assets/vue/vuelidate.min.js') }}"></script>
<script src="{{ asset('public/assets/vue/validators.min.js') }}"></script>
<script src="{{ asset('public/assets/vue/axios.min.js') }}"></script>
<script src="{{ asset('public/assets/vue/vue-toastr.umd.min.js') }}"></script>
<script src="{{ asset('public/assets/vue/vue-loading-overlay.js') }}"></script>

<script src="{{ asset('public/js/app.js') }}"></script>


<script>

// loading
Vue.use(VueLoading);
Vue.component('loading', VueLoading);

// intialize vuelidate
Vue.use(window.vuelidate.default);
// import rules from vuelidate
const {required, requiredIf, numeric, minValue, maxValue, minLength, maxLength, email, sameAs, helpers} = window.validators;

const regxAlfaWithSpace = (value, vm) => {
  if (value == '') return true
  return /^[A-Z a-z]+$/.test(value)
}

const regxEmailAddress = (value, vm) => {
  if (value == '') return true
  return /^([a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value)
}

const regxNumeric = (value, vm) => {
  if (value == '') return true
  return /^[0-9]+$/.test(value)
}

const regxPassword = (value, vm) => {
  if (value == '') return true
  return /^[a-zA-Z0-9!#\?\^\[\]{}+=._-]{8,32}$/.test(value)
}

//Toastr Doc
//http://s4l1h.github.io/vue-toastr/toast_options.html
let navBarVue = new Vue({
    el: '#app-nav',
    mounted() {
        // Toastr initialization
        this.$toastr.defaultTimeout = 10000; // default timeout : 5000
        this.$toastr.defaultClassNames = ["animated", "zoomInUp"];
        this.$toastr.defaultPosition = "toast-top-right";
        this.$toastr.defaultStyle = { "margin-top": "60px" };
        
        @if(session()->has('msg') && session()->has('msg_class'))
          @if(session()->get('msg_class') == 'alert alert-danger')
            this.$toastr.e("{!! session()->get('msg') !!}", "{{ session()->get('msg_title') }}");
          @endif

          @if(session()->get('msg_class') == 'alert alert-success')
            this.$toastr.s("{!! session()->get('msg') !!}", "{{ session()->get('msg_title') }}");
          @endif
        @endif
    }
});
</script>
@stack('page_js')
</body>
</html>
