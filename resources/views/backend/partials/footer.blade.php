       <footer class="footer">
           <div class="container">
               <div class="row align-items-center flex-row-reverse">
                   <div class="col-md-12 col-sm-12 text-center">
                       Copyright &copy; {{ date('Y') }} <span class="text-primary">
                           {{ \App\Models\Setting::first()->name ?? 'Pinnacle Alt’s Platform' }}
                       </span> All rights reserved
                   </div>
               </div>
           </div>
       </footer>
