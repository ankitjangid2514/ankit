   {{-- <div class="container-fluid">
            <div class="row" style="background: var(--primary);padding: 10px;">
                <div class="col-12">

                    <span style="color:#fff;">SP Panna</span>
                </div>
            </div>
        </div> --}}
        <style>
            /* Custom styles for centering the alert */
            .custom-alert {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                z-index: 1050; /* Ensure it appears above other elements */
                width: 80%; /* Adjust width as needed */
                max-width: 400px;
            }
        </style>
        
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show custom-alert" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show custom-alert" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Automatically hide the alert after 5 seconds (5000 ms)
                setTimeout(() => {
                    const alerts = document.querySelectorAll('.alert');
                    alerts.forEach(alert => alert.classList.remove('show')); // Remove the "show" class to hide the alert
                }, 1000); // Adjust time as needed
            });
        </script>


   <div class="container" style=" margin-bottom: 30px; height:95vh;">
       <form method="Post" id="game_form" action="{{ route('game_insert_game') }}">
           @csrf
           <div class="row">
               <span id="output"></span>
               <div class="col-12">
                   <div class="form-group">
                       <label class="form-label" for="gdate">Choose Date</label>
                       <input type="date" class="form-control" name="gdate" id="gdate" readonly
                           placeholder="Choose Date" value="" />
                   </div>
               </div>

               <div class="col-12" id="groupbox">
                   <p class="session_title">Choose Session</p>
                   <div class="form-group">
                       @if ($current_time <= $open_time)
                           <label class="form-label" for="timetype" style="width: 100px; float: left;">
                               <input type="radio" name="timetype" id="timetype1" value="open" checked />
                               Open </label>

                           <label class="form-label" for="timetype">
                               <input type="radio" name="timetype" id="timetype2" value="close" />
                               Close </label>
                       @else
                           <label class="form-label" for="timetype" style="width: 100px; float: left;">
                               <input type="radio" name="timetype" id="timetype1" value="open" disabled />
                               Open </label>

                           <label class="form-label" for="timetype">
                               <input type="radio" name="timetype" id="timetype2" value="close" checked />
                               Close </label>
                       @endif
                   </div>
               </div>


               <div class="container ">
                   <div class="row d-flex m-0 justify-content-between">
                       <div class="form-group  text-center" style="width: 45%; ">
                           <label class="form-label" for="panna_s">Digit</label>
                           <input type="number" class="form-control" name="panna_s" id="panna_s"
                               placeholder="Enter Panna" required="required">
                       </div>
                       <div class="form-group text-center" style="width: 45%;">
                           <label class="form-label" for="amount">Enter Amount</label>
                           <input type="number" class="form-control" name="amount" id="amount"
                               placeholder="Enter Amount" required="required">
                       </div>
                   </div>


                   <input type="hidden" name="all_bids" id="all_bids">

                   <!-- Button to trigger the JavaScript function to generate combinations -->
                   <div class="row ">
                       <div class=" w-100 mt-3 mx-4 ">
                           <button type="button" id="sp_btn" class="btn btn-primary w-100 mb-2">Add</button>
                       </div>
                   </div>

                   <!-- Table to display the combinations (hidden initially) -->
                   {{-- <h3 class="mt-4 text-white">Generated Combinations:</h3> --}}
                   <div class="table-container">
                       <table class="table table-striped table-bordered" id="combinations-table" style="display: none;">
                           <thead>
                               <tr>
                                   <th class="text-white">Panna</th>
                                   <th class="text-white">Point</th>
                                   <th class="text-white">Action</th>
                               </tr>
                           </thead>
                           <tbody id="combinations-table-body"></tbody> <!-- This will hold the combinations -->
                       </table>
                   </div>

                   <!-- Buttons for actions -->
                   <div class="col-12 mt-3">
                       <input type="hidden" name="gtype_id" id="gtype" value="{{ $gtype_id }}">
                       <input type="hidden" name="market_id" id="game" value="{{ $market_id }}">
                   </div>
                   <div id="summary-container" style="display: none;">
                       <p class="text-white " style="font-size: 1.5rem;">Generated Pannas: <span id="panna-count"
                               class="text-white ">0</span></p>
                       <p class="text-white " style="font-size: 1.5rem;">Total Amount: <span id="total-amount"
                               class="text-white ">0</span></p>
                   </div>

                   <div class="row justify-content-center mb-5 " id="bid-submit-container" style="display: none;">
                       <div class="col-12 col-md-6 mb-2">
                           <input type="submit" id="submit-all-bids" class="btn btn-success w-100"
                               value="Submit All Bids">
                       </div>
                       <div class="col-12 col-md-6">
                           <button type="button" id="cancel-bids" class="btn btn-danger w-100">Cancel
                               Bids</button>
                       </div>
                   </div>


               </div>

           </div>
       </form>
       <!-- Toast Message -->
       <div id="toast-message" class="toast-container">
           <span id="toast-content"></span>
           <button id="toast-close" class="toast-close-btn">&times;</button>
       </div>
   </div>

   !-- Existing Script -->
   <script>
       // Function to generate all possible 3-digit combinations
       function getCombinations(str, length) {
           let result = [];

           // Helper function to generate combinations recursively
           function combine(prefix, start) {
               if (prefix.length === length) {
                   result.push(prefix.padStart(length, '0'));
                   return;
               }
               for (let i = start; i < str.length; i++) {
                   combine(prefix + str[i], i + 1);
               }
           }
           combine('', 0);

           result.sort((a, b) => parseInt(a) - parseInt(b));
           const filteredArr = result.filter(item => item.includes('0'));
           const filteredArr1 = result.filter(item => !item.includes('0'));

           const transformedArr = filteredArr.map(item => {
               let newItem = item.replace('0', '');
               newItem += '0';
               return newItem;
           });

           const combinedArr = [...transformedArr, ...filteredArr1];
           const sortedArr = combinedArr.sort((a, b) => parseInt(a) - parseInt(b));

           return sortedArr;
       }

       function generateCombinations() {
           const digit = document.getElementById('panna_s').value;
           const amount = document.getElementById('amount').value;

           if (digit && amount) {
               let arr = [...new Set(digit.split(''))];
               arr.sort((a, b) => a - b);
               let digits = arr.join('');
               const singlePanna = getCombinations(digits, 3);

               const combinationsTableBody = document.getElementById('combinations-table-body');
               combinationsTableBody.innerHTML = '';

               singlePanna.forEach(item => {
                   const tr = document.createElement('tr');
                   const tdPanna = document.createElement('td');
                   const pannaDiv = document.createElement('div');
                   pannaDiv.className = 'scrollable-content';
                   pannaDiv.textContent = item;
                   tdPanna.appendChild(pannaDiv);

                   const tdPoint = document.createElement('td');
                   const amountDiv = document.createElement('div');
                   amountDiv.className = 'scrollable-content';
                   amountDiv.textContent = amount;
                   tdPoint.appendChild(amountDiv);

                   const tdAction = document.createElement('td');
                   const deleteButton = document.createElement('button');
                   deleteButton.className = 'btn btn-danger';
                   deleteButton.textContent = 'Delete';
                   deleteButton.onclick = function() {
                       tr.remove();
                       updateAllBids();
                   };
                   tdAction.appendChild(deleteButton);

                   tr.appendChild(tdPanna);
                   tr.appendChild(tdPoint);
                   tr.appendChild(tdAction);
                   combinationsTableBody.appendChild(tr);
               });

               document.getElementById('combinations-table').style.display = 'table';
               document.getElementById('bid-submit-container').style.display = 'flex';
               updateAllBids();

               // Show success message
               showToast('Combinations generated successfully!');
           } else {
               showToast('Please enter both number and amount.', 'error');
           }
       }

       function updateAllBids() {
           const tableRows = document.querySelectorAll('#combinations-table-body tr');
           const allBids = [];
           let totalAmount = 0;

           tableRows.forEach(row => {
               const panna_s = row.children[0].textContent.trim();
               const amount = parseInt(row.children[1].textContent.trim(), 10);
               allBids.push({
                   panna_s,
                   amount
               });
               totalAmount += amount;
           });

           document.getElementById('panna-count').textContent = tableRows.length;
           document.getElementById('total-amount').textContent = totalAmount;
           document.getElementById('all_bids').value = JSON.stringify(allBids);

           const summaryContainer = document.getElementById('summary-container');
           if (tableRows.length > 0) {
               summaryContainer.style.display = 'block';
           } else {
               summaryContainer.style.display = 'none';
           }
       }

       document.querySelector('form').addEventListener('submit', function(e) {
           const panna = document.getElementById('panna_s').value;
           const amount = document.getElementById('amount').value;

           if (panna && amount) {
               let uniqueDigits = [...new Set(panna.split(''))].sort((a, b) => a - b);
               const combinations = getCombinations(uniqueDigits.join(''), 3);

               const allBids = combinations.map(panna => ({
                   panna_s: panna,
                   amount
               }));
               document.getElementById('all_bids').value = JSON.stringify(allBids);
           } else {
               e.preventDefault();
               showToast('Please fill in all fields.', 'error');
           }
       });

       document.getElementById('sp_btn').addEventListener('click', generateCombinations);

       // Toast functionality
       function showToast(message, type = 'success') {
           const toast = document.getElementById('toast-message');
           const toastContent = document.getElementById('toast-content');
           const toastClose = document.getElementById('toast-close');

           toastContent.textContent = message;
           toast.className = `toast-container ${type}`;
           toast.style.display = 'flex';

           setTimeout(() => {
               toast.style.display = 'none';
           }, 500);

           toastClose.onclick = () => {
               toast.style.display = 'none';
           };
       }
   </script>

   <style>
       /* Toast Styles */
       .toast-container {
           position: fixed;
           top: 50%;
           left: 50%;
           transform: translate(-50%, -50%);
           z-index: 9999;
           display: none;
           padding: 20px;
           border-radius: 5px;
           background-color: var(--primary);
           color: white;
           box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
       }
       td{
        color: #fff;
       }
       .toast-close-btn {
           background: none;
           border: none;
           color: white;
           font-size: 20px;
           font-weight: bold;
           cursor: pointer;
           margin-left: 10px;
       }

       .toast-container.error {
           background-color: #dc3545;
       }
   </style>



