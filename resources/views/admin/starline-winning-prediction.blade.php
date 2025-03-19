@extends('admin.layouts.main')
@section('title')
Starline Winning Prediction
@endsection
@section('container')

    <div class="main-content">	<div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-xl-12 col-md-12">
                    <div class="row">
                        <div class="col-sm-12">
                        <div class="card">
                                <div class="card-body">
                                <h4 class="card-title">Winning prediction</h4>

                                <form class="theme-form mega-form" id="stralineWinningpredictFrm" name="stralineWinningpredictFrm" method="post" autocomplete="off">
                                    <div class="row">
                                    <div class="form-group col-md-2">
                                        <label>Date</label>
                                                                        <div class="date-picker">
                                            <div class="input-group">
                                                <input class="form-control digits" type="date" value="2024-09-06" name="result_date" id="result_date"  max="2024-09-06" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Game Name</label>
                                        <select id="win_game_name" name="win_game_name" class="form-control">
                                            <option value=''>-Select Game Name-</option>
                                                                                <option value="1">10:00 PM</option>
                                                                                <option value="2">11:00 AM</option>
                                                                                <option value="3">12:00 PM</option>
                                                                                <option value="4">1:00 PM</option>
                                                                                <option value="5">2:00 PM</option>
                                                                                <option value="6">3:00 PM</option>
                                                                                <option value="7">4:00 PM</option>
                                                                                <option value="8">5:00 PM</option>
                                                                                <option value="9">6:00 PM</option>
                                                                                <option value="10">7:00 PM</option>
                                                                                <option value="11">8:00 PM</option>
                                                                                <option value="12">9:00 PM</option>
                                                                                <option value="13">10:00 AM</option>
                                                                                <option value="14">11:00 AM</option>
                                                                                <option value="16">12:00pm</option>
                                                                            </select>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label>Number</label>
                                        <select class="form-control select2" name="winning_ank" id="winning_ank">
                                                                                    <option value="000">000</option>
                                                                                    <option value="100">100</option>
                                                                                    <option value="110">110</option>
                                                                                    <option value="111">111</option>
                                                                                    <option value="112">112</option>
                                                                                    <option value="113">113</option>
                                                                                    <option value="114">114</option>
                                                                                    <option value="115">115</option>
                                                                                    <option value="116">116</option>
                                                                                    <option value="117">117</option>
                                                                                    <option value="118">118</option>
                                                                                    <option value="119">119</option>
                                                                                    <option value="120">120</option>
                                                                                    <option value="122">122</option>
                                                                                    <option value="123">123</option>
                                                                                    <option value="124">124</option>
                                                                                    <option value="125">125</option>
                                                                                    <option value="126">126</option>
                                                                                    <option value="127">127</option>
                                                                                    <option value="128">128</option>
                                                                                    <option value="129">129</option>
                                                                                    <option value="130">130</option>
                                                                                    <option value="133">133</option>
                                                                                    <option value="134">134</option>
                                                                                    <option value="135">135</option>
                                                                                    <option value="136">136</option>
                                                                                    <option value="137">137</option>
                                                                                    <option value="138">138</option>
                                                                                    <option value="139">139</option>
                                                                                    <option value="140">140</option>
                                                                                    <option value="144">144</option>
                                                                                    <option value="145">145</option>
                                                                                    <option value="146">146</option>
                                                                                    <option value="147">147</option>
                                                                                    <option value="148">148</option>
                                                                                    <option value="149">149</option>
                                                                                    <option value="150">150</option>
                                                                                    <option value="155">155</option>
                                                                                    <option value="156">156</option>
                                                                                    <option value="157">157</option>
                                                                                    <option value="158">158</option>
                                                                                    <option value="159">159</option>
                                                                                    <option value="160">160</option>
                                                                                    <option value="166">166</option>
                                                                                    <option value="167">167</option>
                                                                                    <option value="168">168</option>
                                                                                    <option value="169">169</option>
                                                                                    <option value="170">170</option>
                                                                                    <option value="177">177</option>
                                                                                    <option value="178">178</option>
                                                                                    <option value="179">179</option>
                                                                                    <option value="180">180</option>
                                                                                    <option value="188">188</option>
                                                                                    <option value="189">189</option>
                                                                                    <option value="190">190</option>
                                                                                    <option value="199">199</option>
                                                                                    <option value="200">200</option>
                                                                                    <option value="220">220</option>
                                                                                    <option value="222">222</option>
                                                                                    <option value="223">223</option>
                                                                                    <option value="224">224</option>
                                                                                    <option value="225">225</option>
                                                                                    <option value="226">226</option>
                                                                                    <option value="227">227</option>
                                                                                    <option value="228">228</option>
                                                                                    <option value="229">229</option>
                                                                                    <option value="230">230</option>
                                                                                    <option value="233">233</option>
                                                                                    <option value="234">234</option>
                                                                                    <option value="235">235</option>
                                                                                    <option value="236">236</option>
                                                                                    <option value="237">237</option>
                                                                                    <option value="238">238</option>
                                                                                    <option value="239">239</option>
                                                                                    <option value="240">240</option>
                                                                                    <option value="244">244</option>
                                                                                    <option value="245">245</option>
                                                                                    <option value="246">246</option>
                                                                                    <option value="247">247</option>
                                                                                    <option value="248">248</option>
                                                                                    <option value="249">249</option>
                                                                                    <option value="250">250</option>
                                                                                    <option value="255">255</option>
                                                                                    <option value="256">256</option>
                                                                                    <option value="257">257</option>
                                                                                    <option value="258">258</option>
                                                                                    <option value="259">259</option>
                                                                                    <option value="260">260</option>
                                                                                    <option value="266">266</option>
                                                                                    <option value="267">267</option>
                                                                                    <option value="268">268</option>
                                                                                    <option value="269">269</option>
                                                                                    <option value="270">270</option>
                                                                                    <option value="277">277</option>
                                                                                    <option value="278">278</option>
                                                                                    <option value="279">279</option>
                                                                                    <option value="280">280</option>
                                                                                    <option value="288">288</option>
                                                                                    <option value="289">289</option>
                                                                                    <option value="290">290</option>
                                                                                    <option value="299">299</option>
                                                                                    <option value="300">300</option>
                                                                                    <option value="330">330</option>
                                                                                    <option value="333">333</option>
                                                                                    <option value="334">334</option>
                                                                                    <option value="335">335</option>
                                                                                    <option value="336">336</option>
                                                                                    <option value="337">337</option>
                                                                                    <option value="338">338</option>
                                                                                    <option value="339">339</option>
                                                                                    <option value="340">340</option>
                                                                                    <option value="344">344</option>
                                                                                    <option value="345">345</option>
                                                                                    <option value="346">346</option>
                                                                                    <option value="347">347</option>
                                                                                    <option value="348">348</option>
                                                                                    <option value="349">349</option>
                                                                                    <option value="350">350</option>
                                                                                    <option value="355">355</option>
                                                                                    <option value="356">356</option>
                                                                                    <option value="357">357</option>
                                                                                    <option value="358">358</option>
                                                                                    <option value="359">359</option>
                                                                                    <option value="360">360</option>
                                                                                    <option value="366">366</option>
                                                                                    <option value="367">367</option>
                                                                                    <option value="368">368</option>
                                                                                    <option value="369">369</option>
                                                                                    <option value="370">370</option>
                                                                                    <option value="377">377</option>
                                                                                    <option value="378">378</option>
                                                                                    <option value="379">379</option>
                                                                                    <option value="380">380</option>
                                                                                    <option value="388">388</option>
                                                                                    <option value="389">389</option>
                                                                                    <option value="390">390</option>
                                                                                    <option value="399">399</option>
                                                                                    <option value="400">400</option>
                                                                                    <option value="440">440</option>
                                                                                    <option value="444">444</option>
                                                                                    <option value="445">445</option>
                                                                                    <option value="446">446</option>
                                                                                    <option value="447">447</option>
                                                                                    <option value="448">448</option>
                                                                                    <option value="449">449</option>
                                                                                    <option value="450">450</option>
                                                                                    <option value="455">455</option>
                                                                                    <option value="456">456</option>
                                                                                    <option value="457">457</option>
                                                                                    <option value="458">458</option>
                                                                                    <option value="459">459</option>
                                                                                    <option value="460">460</option>
                                                                                    <option value="466">466</option>
                                                                                    <option value="467">467</option>
                                                                                    <option value="468">468</option>
                                                                                    <option value="469">469</option>
                                                                                    <option value="470">470</option>
                                                                                    <option value="477">477</option>
                                                                                    <option value="478">478</option>
                                                                                    <option value="479">479</option>
                                                                                    <option value="480">480</option>
                                                                                    <option value="488">488</option>
                                                                                    <option value="489">489</option>
                                                                                    <option value="490">490</option>
                                                                                    <option value="499">499</option>
                                                                                    <option value="500">500</option>
                                                                                    <option value="550">550</option>
                                                                                    <option value="555">555</option>
                                                                                    <option value="556">556</option>
                                                                                    <option value="557">557</option>
                                                                                    <option value="558">558</option>
                                                                                    <option value="559">559</option>
                                                                                    <option value="560">560</option>
                                                                                    <option value="566">566</option>
                                                                                    <option value="567">567</option>
                                                                                    <option value="568">568</option>
                                                                                    <option value="569">569</option>
                                                                                    <option value="570">570</option>
                                                                                    <option value="577">577</option>
                                                                                    <option value="578">578</option>
                                                                                    <option value="579">579</option>
                                                                                    <option value="580">580</option>
                                                                                    <option value="588">588</option>
                                                                                    <option value="589">589</option>
                                                                                    <option value="590">590</option>
                                                                                    <option value="599">599</option>
                                                                                    <option value="600">600</option>
                                                                                    <option value="660">660</option>
                                                                                    <option value="666">666</option>
                                                                                    <option value="667">667</option>
                                                                                    <option value="668">668</option>
                                                                                    <option value="669">669</option>
                                                                                    <option value="670">670</option>
                                                                                    <option value="677">677</option>
                                                                                    <option value="678">678</option>
                                                                                    <option value="679">679</option>
                                                                                    <option value="680">680</option>
                                                                                    <option value="688">688</option>
                                                                                    <option value="689">689</option>
                                                                                    <option value="690">690</option>
                                                                                    <option value="699">699</option>
                                                                                    <option value="700">700</option>
                                                                                    <option value="770">770</option>
                                                                                    <option value="777">777</option>
                                                                                    <option value="778">778</option>
                                                                                    <option value="779">779</option>
                                                                                    <option value="780">780</option>
                                                                                    <option value="788">788</option>
                                                                                    <option value="789">789</option>
                                                                                    <option value="790">790</option>
                                                                                    <option value="799">799</option>
                                                                                    <option value="800">800</option>
                                                                                    <option value="880">880</option>
                                                                                    <option value="888">888</option>
                                                                                    <option value="889">889</option>
                                                                                    <option value="890">890</option>
                                                                                    <option value="899">899</option>
                                                                                    <option value="900">900</option>
                                                                                    <option value="990">990</option>
                                                                                    <option value="999">999</option>
                                                                                </select>

                                    </div>

                                    <div class="form-group col-md-2">
                                            <label>&nbsp;</label>
                                            <button type="submit" class="btn btn-primary btn-block" id="submitBtn" name="submitBtn">Submit</button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                    <div id="error"></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                        <h4 class="card-title">Winning Prediction List
                        </h4>
                            <div class="mt-3">
                                <div class="bs_box bs_box_light">
                                    <i class="mdi mdi-gavel mr-1"></i>
                                    <span>Total Bid Amount</span>
                                    <b><span id="t_bid">0</span></b>
                                </div>

                                <div class="bs_box bs_box_light">
                                    <i class="mdi mdi-wallet mr-1"></i>
                                    <span>Total Winning Amount</span>
                                    <b><span id="t_winneing_amt">0</span></b>
                                </div>
                            </div>

                            <div class="mt-3">
                            <table class="table table-striped table-bordered" id="winners">
                                <thead>
                                    <tr>
                                    <th>#</th>
                                    <th>User Name</th>
                                    <th>Bid Points</th>
                                    <th>Winning Amount</th>
                                    <th>Type</th>
                                    <th>Bid TX ID</th>
                                    </tr>
                                </thead>


                            </table>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @section('script')

        <script type="text/javascript">

            $(document).ready(function() {
                // Initialize the DataTable
                var table = $('#winners').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('starline_winning_prediction_list') }}',
                        type: 'get',
                        data: function(d) {
                            d._token = '{{ csrf_token() }}'; // Include CSRF token for security
                        }
                    },
                    columns: [{
                            data: 'id'
                        },
                        {
                            data: 'name'
                        },
                        {
                            data: 'bid_point'
                        },
                        {
                            data: 'winning_amount'
                        },
                        {
                            data: 'bid_type'
                        },
                        {
                            data: 'bid_id'
                        },

                    ]
                });
            });
        </script>

    @endsection

@endsection
