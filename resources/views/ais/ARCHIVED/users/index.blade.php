<x-admin-layout>
    <x-slot name="title">Users</x-slot>
    
    <div class="row">
        <div class="col-12">
            <div class="d-flex">
                <div class="dropdown mr-2">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        Sort by
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item" href="#"><i class="bi-hourglass-top"></i> Date (New First)</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi-hourglass-bottom"></i> Date (Old First)</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi-arrow-up"></i> Alphabetical (A-Z First)</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi-arrow-down"></i> Alphabetical (Z-A First)</a></li>
                    </ul>
                </div>
                <button type="button" class="btn btn-secondary mr-2">Export CSV</button>

                <div class="form-check mr-2 mt-1">
                    <input class="form-check-input" type="checkbox" value="" id="staffCheckChecked" checked="">
                    <label class="form-check-label" for="staffCheckChecked">
                        <i class="bi-hammer text-danger"></i> Staff Members
                    </label>
                </div>

                <div class="form-check mr-2 mt-1">
                    <input class="form-check-input" type="checkbox" value="" id="premiumCheckChecked" checked="">
                    <label class="form-check-label" for="premiumCheckChecked">
                        <i class="bi-award-fill text-primary"></i> Premium
                    </label>
                </div>

                <span class="mr-2 mt-1 text-muted">Showing 100/1,592</span>
            </div>

            <table class="table table-bordered my-3">
                <thead class="bg-gray-500">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Avatar</th>
                        <th scope="col">Username</th>
                        <th scope="col">Date Created</th>
                        <th scope="col">Marks</th>
                        <th scope="col">Data Point</th>
                        <th scope="col">Data Point</th>
                        <th scope="col">Data Point</th>
                        <th scope="col">Data Point</th>
                        <th scope="col">Data Point</th>
                        <th scope="col">Data Point</th>
                    </tr>
                </thead>
                <tbody style="border-top: 4px;">
                    <tr>
                        <th scope="row">127</th>
                        <th><a href="profile.html"><img src="img/blocky.png" style="max-width:100px"></a></th>
                        <td><a href="#">Rob</a></td>
                        <td>2022-04-13</td>
                        <td><i class="bi-hammer text-danger"></i> <i class="bi-award-fill text-primary"></i></td>
                        <td>?</td>
                        <td>!</td>
                        <td>#</td>
                        <td>%</td>
                        <td>$</td>
                        <td>@</td>
                    </tr>
                    <tr>
                        <th scope="row">91</th>
                        <th><a href="profile.html"><img src="img/blocky.png" style="max-width:100px"></a></th>
                        <td><a href="#">IsaacHimerPlayzzBV_YT</a></td>
                        <td>2022-03-26</td>
                        <td><i class="bi-award-fill text-primary"></i> <i class="bi-camera-reels-fill text-warning"></i></td>
                        <td>?</td>
                        <td>!</td>
                        <td>#</td>
                        <td>%</td>
                        <td>$</td>
                        <td>@</td>
                    </tr>
                    <tr>
                        <th scope="row">50</th>
                        <th><a href="profile.html"><img src="img/blocky.png" style="max-width:100px"></a></th>
                        <td><a href="#">ChickenWingEater</a></td>
                        <td>2021-12-04</td>
                        <td><i class="bi-robot text-info"></td>
                        <td>?</td>
                        <td>!</td>
                        <td>#</td>
                        <td>%</td>
                        <td>$</td>
                        <td>@</td>
                    </tr>
                </tbody>
            </table>



        </div>
    </div>


</x-admin-layout>