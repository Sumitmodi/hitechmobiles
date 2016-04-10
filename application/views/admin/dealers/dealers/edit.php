<script type="text/javascript">
    var apikey = '<?php echo $this->config->item('hitech_apikey'); ?>';
    var postUrl = '<?php echo base_url('api/dealers/edit'); ?>';
    <?php if($this->uri->segment(4)>0){ ?>
    var editMode = true;
    var editId = <?php echo $this->uri->segment(4);?>;
    <?php }else{ ?>
    var editMode = false;
    <?php } ?>
</script>
<div class="container" ng-controller="dealersEditCtrl" ng-scope="scope">
    <form role="form" method="POST" action="" enctype="multipart/form-data" ng-submit="addDealer()">
        <div class="col-md-4"></div>
        <div class="col-md-5 faint-bkg">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class="col-md-12">
                        <h3 class="faint-color" ng-if="editMode == null ||editMode == false">ADD A DEALER</h3>
                        <h3 class="faint-color" ng-if="editMode == true">EDIT DEALER : {{dealers.first_name}} {{dealers.last_name}}</h3>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="text" class="faint-color">First Name</label>
                            <input type="text" class="form-control" id="text"
                                   placeholder="Enter first name"
                                   ng-model="dealers.first_name">
                        </div>
                        <div class="form-group">
                            <label for="text" class="faint-color">Last Name</label>
                            <input type="text" class="form-control" id="text"
                                   placeholder="Enter last name"
                                   ng-model="dealers.last_name">
                        </div>
                        <div>
                            <label class="faint-color">Company Name</label>
                            <input type="text" class="form-control" id="text"
                                   placeholder="Enter company name"
                                   ng-model="dealers.company_name">
                        </div>

                        <div>
                            <label class="faint-color">Email</label>
                            <input type="text" class="form-control" id="text"
                                   placeholder="Enter email"
                                   ng-model="dealers.email">
                        </div>
                        <div>
                            <label class="faint-color">Phone</label>
                            <input type="text" class="form-control" id="text"
                                   placeholder="Enter page phone numbe"
                                   ng-model="dealers.phone">
                        </div>
                        <div>
                            <label class="faint-color">Website</label>
                            <input type="text" class="form-control" id="text"
                                   placeholder="Enter enter website"
                                   ng-model="dealers.website">
                        </div>


                        <div>
                            <label class="faint-color">Gst No.</label>
                            <input type="text" class="form-control" id="text"
                                   placeholder="Enter gst number"
                                   ng-model="dealers.gst_no">
                        </div>
                        <div>
                            <label class="faint-color">Address</label>
                            <textarea class="form-control" id="text"
                                   placeholder="Enter address"
                                   ng-model="dealers.address"></textarea>
                        </div>
                        <div>
                            <label class="faint-color">City</label>
                            <input type="text" class="form-control" id="text"
                                   placeholder="Enter enter city"
                                   ng-model="dealers.city">
                        </div>
                        <div>
                            <label class="faint-color">Post Code</label>
                            <input type="text" class="form-control" id="text"
                                   placeholder="Enter enter post code"
                                   ng-model="dealers.post_code">
                        </div>
                        <div>
                            <label class="faint-color">Country</label>
                            <select ng-model="dealers.country" class="form-control">
                                <option value="">Select Country</option>
                                <option value="Andorra">Andorra</option>
                                <option value="United Arab Emirates">United Arab Emirates</option>
                                <option value="Afghanistan">Afghanistan</option>
                                <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                <option value="Anguilla">Anguilla</option>
                                <option value="Albania">Albania</option>
                                <option value="Armenia">Armenia</option>
                                <option value="Angola">Angola</option>
                                <option value="Antarctica">Antarctica</option>
                                <option value="Argentina">Argentina</option>
                                <option value="American Samoa">American Samoa</option>
                                <option value="Austria">Austria</option>
                                <option value="Australia">Australia</option>
                                <option value="Aruba">Aruba</option>
                                <option value="Azerbaijan">Azerbaijan</option>
                                <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                <option value="Barbados">Barbados</option>
                                <option value="Bangladesh">Bangladesh</option>
                                <option value="Belgium">Belgium</option>
                                <option value="Burkina Faso">Burkina Faso</option>
                                <option value="Bulgaria">Bulgaria</option>
                                <option value="Bahrain">Bahrain</option>
                                <option value="Burundi">Burundi</option>
                                <option value="Benin">Benin</option>
                                <option value="Saint Barthelemy">Saint Barthelemy</option>
                                <option value="Bermuda">Bermuda</option>
                                <option value="Brunei">Brunei</option>
                                <option value="Bolivia">Bolivia</option>
                                <option value="Brazil">Brazil</option>
                                <option value="Bahamas">Bahamas</option>
                                <option value="Bhutan">Bhutan</option>
                                <option value="Bouvet Island">Bouvet Island</option>
                                <option value="Botswana">Botswana</option>
                                <option value="Belarus">Belarus</option>
                                <option value= "Belize">Belize</option>
                                <option value= "Canada">Canada</option>
                                <option value= "Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                                <option value= "Congo, Democratic Republic of the">Congo, Democratic Republic of the</option>
                                <option value= "Central African Republic">Central African Republic</option>
                                <option value= "Congo, Republic of the">Congo, Republic of the</option>
                                <option value= "Switzerland">Switzerland</option>
                                <option value= "Cote d'Ivoire">Cote d'Ivoire</option>
                                <option value= "Cook Islands">Cook Islands</option>
                                <option value= "Chile">Chile</option>
                                <option value= "Cameroon">Cameroon</option>
                                <option value= "China">China</option>
                                <option value= "Colombia">Colombia</option>
                                <option value= "Costa Rica">Costa Rica</option>
                                <option value= "Cuba">Cuba</option>
                                <option value= "Cape Verde">Cape Verde</option>
                                <option value= "Curacao">Curacao</option>
                                <option value= "Christmas Island">Christmas Island</option>
                                <option value= "Cyprus">Cyprus</option>
                                <option value= "Czech Republic">Czech Republic</option>
                                <option value= "Germany">Germany</option>
                                <option value= "Djibouti">Djibouti</option>
                                <option value= "Denmark">Denmark</option>
                                <option value= "Dominica">Dominica</option>
                                <option value= "Dominican Republic">Dominican Republic</option>
                                <option value= "Algeria">Algeria</option>
                                <option value= "Ecuador">Ecuador</option>
                                <option value= "Estonia">Estonia</option>
                                <option value= "Egypt">Egypt</option>
                                <option value= "Western Sahara">Western Sahara</option>
                                <option value= "Eritrea">Eritrea</option>
                                <option value= "Spain">Spain</option>
                                <option value= "Ethiopia">Ethiopia</option>
                                <option value= "Finland">Finland</option>
                                <option value= "Fiji">Fiji</option>
                                <option value= "Falkland Islands (Islas Malvinas)">Falkland Islands (Islas Malvinas)</option>
                                <option value= "Micronesia, Federated States of">Micronesia, Federated States of</option>
                                <option value= "Faroe Islands">Faroe Islands</option>
                                <option value= "France">France</option>
                                <option value= "France, Metropolitan">France, Metropolitan</option>
                                <option value= "Gabon">Gabon</option>
                                <option value= "United Kingdom">United Kingdom</option>
                                <option value= "Grenada">Grenada</option>
                                <option value= "Georgia">Georgia</option>
                                <option value= "French Guiana">French Guiana</option>
                                <option value= "Guernsey">Guernsey</option>
                                <option value= "Ghana">Ghana</option>
                                <option value= "Gibraltar">Gibraltar</option>
                                <option value= "Greenland">Greenland</option>
                                <option value= "Gambia, The">Gambia, The</option>
                                <option value= "Guinea">Guinea</option>
                                <option value= "Guadeloupe">Guadeloupe</option>
                                <option value= "Equatorial Guinea">Equatorial Guinea</option>
                                <option value= "Greece">Greece</option>
                                <option value= "South Georgia and the Islands">South Georgia and the Islands</option>
                                <option value= "Guatemala">Guatemala</option>
                                <option value= "Guam">Guam</option>
                                <option value= "Guinea-Bissau">Guinea-Bissau</option>
                                <option value= "Guyana">Guyana</option>
                                <option value= "Hong Kong">Hong Kong</option>
                                <option value= "Heard Island and McDonald Islands">Heard Island and McDonald Islands</option>
                                <option value= "Honduras">Honduras</option>
                                <option value= "Croatia">Croatia</option>
                                <option value= "Haiti">Haiti</option>
                                <option value= "Hungary">Hungary</option>
                                <option value= "Indonesia">Indonesia</option>
                                <option value= "Ireland">Ireland</option>
                                <option value= "Israel">Israel</option>
                                <option value= "Isle of Man">Isle of Man</option>
                                <option value= "India">India</option>
                                <option value= "British Indian Ocean Territory">British Indian Ocean Territory</option>
                                <option value= "Iraq">Iraq</option>
                                <option value= "Iran">Iran</option>
                                <option value= "Iceland">Iceland</option>
                                <option value= "Italy">Italy</option>
                                <option value= "Jersey">Jersey</option>
                                <option value= "Jamaica">Jamaica</option>
                                <option value= "Jordan">Jordan</option>
                                <option value= "Japan">Japan</option>
                                <option value= "Kenya">Kenya</option>
                                <option value= "Kyrgyzstan">Kyrgyzstan</option>
                                <option value= "Cambodia">Cambodia</option>
                                <option value= "Kiribati">Kiribati</option>
                                <option value= "Comoros">Comoros</option>
                                <option value= "Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                <option value= "Korea, North">Korea, North</option>
                                <option value= "Korea, South">Korea, South</option>
                                <option value= "Kuwait">Kuwait</option>
                                <option value= "Cayman Islands">Cayman Islands</option>
                                <option value= "Kazakhstan">Kazakhstan</option>
                                <option value= "Laos">Laos</option>
                                <option value= "Lebanon">Lebanon</option>
                                <option value= "Saint Lucia">Saint Lucia</option>
                                <option value= "Liechtenstein">Liechtenstein</option>
                                <option value= "Sri Lanka">Sri Lanka</option>
                                <option value= "Liberia">Liberia</option>
                                <option value= "Lesotho">Lesotho</option>
                                <option value= "Lithuania">Lithuania</option>
                                <option value= "Luxembourg">Luxembourg</option>
                                <option value= "Latvia">Latvia</option>
                                <option value= "Libya">Libya</option>
                                <option value= "Morocco">Morocco</option>
                                <option value= "Monaco">Monaco</option>
                                <option value= "Moldova">Moldova</option>
                                <option value= "Montenegro">Montenegro</option>
                                <option value= "Saint Martin">Saint Martin</option>
                                <option value= "Madagascar">Madagascar</option>
                                <option value= "Marshall Islands">Marshall Islands</option>
                                <option value= "Macedonia">Macedonia</option>
                                <option value= "Mali">Mali</option>
                                <option value= "Burma">Burma</option>
                                <option value= "Mongolia">Mongolia</option>
                                <option value= "Macau">Macau</option>
                                <option value= "Northern Mariana Islands">Northern Mariana Islands</option>
                                <option value= "Martinique">Martinique</option>
                                <option value= "Mauritania">Mauritania</option>
                                <option value= "Montserrat">Montserrat</option>
                                <option value= "Malta">Malta</option>
                                <option value= "Mauritius">Mauritius</option>
                                <option value= "Maldives">Maldives</option>
                                <option value= "Malawi">Malawi</option>
                                <option value= "Mexico">Mexico</option>
                                <option value= "Malaysia">Malaysia</option>
                                <option value= "Mozambique">Mozambique</option>
                                <option value= "Namibia">Namibia</option>
                                <option value= "New Caledonia">New Caledonia</option>
                                <option value= "Niger">Niger</option>
                                <option value= "Norfolk Island">Norfolk Island</option>
                                <option value= "Nigeria">Nigeria</option>
                                <option value= "Nicaragua">Nicaragua</option>
                                <option value= "Netherlands">Netherlands</option>
                                <option value= "Norway">Norway</option>
                                <option value= "Nepal">Nepal</option>
                                <option value= "Nauru">Nauru</option>
                                <option value= "Niue">Niue</option>
                                <option value= "New Zealand">New Zealand</option>
                                <option value= "Oman">Oman</option>
                                <option value= "Panama">Panama</option>
                                <option value= "Peru">Peru</option>
                                <option value= "French Polynesia">French Polynesia</option>
                                <option value= "Papua New Guinea">Papua New Guinea</option>
                                <option value= "Philippines">Philippines</option>
                                <option value= "Pakistan">Pakistan</option>
                                <option value= "Poland">Poland</option>
                                <option value= "Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                                <option value= "Pitcairn Islands">Pitcairn Islands</option>
                                <option value= "Puerto Rico">Puerto Rico</option>
                                <option value= "Gaza Strip">Gaza Strip</option>
                                <option value= "West Bank">West Bank</option>
                                <option value= "Portugal">Portugal</option>
                                <option value= "Palau">Palau</option>
                                <option value= "Paraguay">Paraguay</option>
                                <option value= "Qatar">Qatar</option>
                                <option value= "Reunion">Reunion</option>
                                <option value= "Romania">Romania</option>
                                <option value= "Serbia">Serbia</option>
                                <option value= "Russia">Russia</option>
                                <option value= "Rwanda">Rwanda</option>
                                <option value= "Saudi Arabia">Saudi Arabia</option>
                                <option value= "Solomon Islands">Solomon Islands</option>
                                <option value= "Seychelles">Seychelles</option>
                                <option value= "Sudan">Sudan</option>
                                <option value= "Sweden">Sweden</option>
                                <option value= "Singapore">Singapore</option>
                                <option value= "Saint Helena, Ascension, and Tristan da Cunha">Saint Helena, Ascension, and Tristan da Cunha</option>
                                <option value= "Slovenia">Slovenia</option>
                                <option value= "Svalbard">Svalbard</option>
                                <option value= "Slovakia">Slovakia</option>
                                <option value= "Sierra Leone">Sierra Leone</option>
                                <option value= "San Marino">San Marino</option>
                                <option value= "Senegal">Senegal</option>
                                <option value= "Somalia">Somalia</option>
                                <option value= "Suriname">Suriname</option>
                                <option value= "South Sudan">South Sudan</option>
                                <option value= "Sao Tome and Principe">Sao Tome and Principe</option>
                                <option value= "El Salvador">El Salvador</option>
                                <option value= "Sint Maarten">Sint Maarten</option>
                                <option value= "Syria">Syria</option>
                                <option value= "Swaziland">Swaziland</option>
                                <option value= "Turks and Caicos Islands">Turks and Caicos Islands</option>
                                <option value= "Chad">Chad</option>
                                <option value= "French Southern and Antarctic Lands">French Southern and Antarctic Lands</option>
                                <option value= "Togo">Togo</option>
                                <option value= "Thailand">Thailand</option>
                                <option value= "Tajikistan">Tajikistan</option>
                                <option value= "Tokelau">Tokelau</option>
                                <option value= "Timor-Leste">Timor-Leste</option>
                                <option value= "Turkmenistan">Turkmenistan</option>
                                <option value= "Tunisia">Tunisia</option>
                                <option value= "Tonga">Tonga</option>
                                <option value= "Turkey">Turkey</option>
                                <option value= "Trinidad and Tobago">Trinidad and Tobago</option>
                                <option value= "Tuvalu">Tuvalu</option>
                                <option value= "Taiwan">Taiwan</option>
                                <option value= "Tanzania">Tanzania</option>
                                <option value= "Ukraine">Ukraine</option>
                                <option value= "Uganda">Uganda</option>
                                <option value= "United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                                <option value= "United States">United States</option>
                                <option value= "Uruguay">Uruguay</option>
                                <option value= "Uzbekistan">Uzbekistan</option>
                                <option value= "Holy See (Vatican City)">Holy See (Vatican City)</option>
                                <option value= "Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
                                <option value= "Venezuela">Venezuela</option>
                                <option value= "British Virgin Islands">British Virgin Islands</option>
                                <option value= "Virgin Islands">Virgin Islands</option>
                                <option value= "Vietnam">Vietnam</option>
                                <option value= "Vanuatu">Vanuatu</option>
                                <option value= "Wallis and Futuna">Wallis and Futuna</option>
                                <option value= "Samoa">Samoa</option>
                                <option value= "Kosovo">Kosovo</option>
                                <option value= "Yemen">Yemen</option>
                                <option value= "Mayotte">Mayotte</option>
                                <option value= "South Africa">South Africa</option>
                                <option value= "Zambia">Zambia</option>
                                <option value= "Zimbabwe">Zimbabw</option>
                            </select>
                        </div>
                        <div>
                            <label class="faint-color">Interest</label>
                            <input type="text" class="form-control" id="text"
                                   placeholder="Enter enter interest"
                                   ng-model="dealers.interest">
                        </div>
                        <div>
                            <label class="faint-color">Est Month Handset Expenditure</label>
                            <input type="text" class="form-control" id="text"
                                   placeholder="Enter enter est month handset expenditure"
                                   ng-model="dealers.est_month_handset_expenditure">
                        </div>
                        <div>
                            <label class="faint-color">Password</label>
                            <input type="password" class="form-control" id="text"
                                   placeholder="Enter enter password"
                                   ng-model="dealers.password">
                        </div>
                        <div>
                            <label class="faint-color">IS pricespy?</label>
                            <select class="form-control" ng-model="dealers.is_pricespy">
                                <option ng-selected="true" ng-value="0">No</option>
                                <option ng-value="1">Yes</option>
                            </select>
                        </div>
                        <div ng-show="dealers.is_pricespy == 1">
                            <label class="faint-color">Pricespy code</label>
                            <input type="text" ng-model="dealers.pricespy_code" placeholder="Pricespy code" class="form-control"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <table class="table">
                                <tr>
                                    <td>
                                        <button class="btn btn-success" type="submit"><span
                                                class="glyphicons glyphicons-plus"></span> Save
                                        </button>
                                    </td>
                                    <td>
                                        <button type="reset" class="btn btn-danger" ng-click="resetProduct()"><span
                                                class="glyphicons glyphicons-remove-2"></span> Reset
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div><!-- container -->

<hr>
<script type="text/javascript">
    var apikey = '<?php echo $this->config->item('hitech_apikey');?>';
    var app = angular.module('app', []);
</script>
<script type="text/javascript" src="<?php echo base_url('assets/angular/dealers/edit.js'); ?>"></script>