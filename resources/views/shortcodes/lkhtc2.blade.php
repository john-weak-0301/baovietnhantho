<div id="vue-select-planning">
    <div class="row md-text-center" ref="selection">
        <div class="col-lg-8 col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-2">
            <div class="select-plan">
                <div class="select-plan__title h2">
                    @if ($isHuuTri)
                        Bạn muốn khoản lương hưu
                    @else
                        Bạn dự kiến sẽ dành
                    @endif

                    <select2 class="select-plan__select" v-model="targetAmount">
                        @foreach($listTargetAmounts as $value)
                            <option value="{{ $value }}">{{ number_format($value, 0, ',', '.') }}</option>
                        @endforeach
                    </select2>

                    @if ($isHuuTri)
                        <br>nhận được theo định kỳ hằng
                        <select2 class="select-plan__select" v-model="subscription">
                            <option value="monthly">Tháng</option>
                            <option value="quarterly">Quý</option>
                            <option value="half_yearly">6 Tháng</option>
                            <option value="yearly">Năm</option>
                        </select2>
                    @else
                        <br>trong thời gian
                        <select2 class="select-plan__select" v-model="estimatedYear">
                            <option value="15">15 năm</option>
                            <option value="10">10 năm</option>
                            <option value="5">5 năm</option>
                        </select2>
                    @endif
                </div>

                <button
                    @click.prevent="showForm"
                    type="submit"
                    class="btn btn-secondary btn-lg btn-shadow">
                    Lập kế hoạch
                </button>
            </div>
        </div>
    </div>

    <collapse-transition :duration="150">
        <div ref="container" v-if="show" class="md-section" style="margin-bottom: 0;">
            <planning-form
                :objectives='@json($plans)'
                :target-name="'{{ $plan }}'"
                :default-target-amount="targetAmount"
                :default-estimated-year="estimatedYear"
                :default-subscription="subscription"
                :run-immediate="true"
                @scroll-top="scrolltTop"
            />
        </div>
    </collapse-transition>
</div>

@push('scripts')
    <script src="{{ mix('js/tool.js') }}"></script>

    <script>
        (function () {
            new Vue({
                el: '#vue-select-planning',

                data: function () {
                    return {
                        show: false,
                        targetAmount: "{{ $isHuuTri ? '8000000' : '500000000' }}",
                        estimatedYear: 15,
                        subscription: 'monthly',
                    }
                },

                mounted: function () {
                    this.$watch(
                        function (vm) { return [vm.targetAmount, vm.estimatedYear] },
                        function () { this.show = false },
                        { immediate: true },
                    )
                },

                methods: {
                    showForm: function () {
                        var vm = this;
                        this.show = true;

                        setTimeout(function () {
                            window.scrollToElement(vm.$refs.container, { duration: 350 });
                        }, 150)
                    },

                    scrolltTop: function () {
                        window.scrollToElement(this.$refs.selection, { duration: 350 });
                    },
                },
            });
        })();
    </script>
@endpush
