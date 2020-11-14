<?= $this->extend('layout/backend_template'); ?>

<?= $this->section('backend_content'); ?>

<div class="row-fluid">
	<div class="span6">
		<div class="widget">
			<div class="widget-header">
				<div class="title">
					<span class="fs1" aria-hidden="true" data-icon="&#xe14a;"></span>Horizontal Form
				</div>
			</div>
			<div class="widget-body">
				<form class="form-horizontal no-margin">
					<div class="control-group">
						<label class="control-label">
							First Name
						</label>
						<div class="controls controls-row">
							<input class="span12" type="text" placeholder="First Name">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">
							Last Name
						</label>
						<div class="controls controls-row">
							<input class="span12" type="text" placeholder="Last Name">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="email1">
							Email ID
						</label>
						<div class="controls">
							<input type="text" name="email1" id="email1" class="span12" placeholder="Enter your Email Address" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="password5">
							Password
						</label>
						<div class="controls">
							<input type="password" name="password1" id="password5" class="span12" placeholder="6 or more characters" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="repPassword">
							Repeat Password
						</label>
						<div class="controls">
							<input type="password" name="repPassword" id="repPassword" class="span12" placeholder="Retype Password" />
						</div>
					</div>

					<div class="control-group">
						<label class="control-label" for="DateofBirthMonth">
							Date of birth
						</label>
						<div class="controls controls-row">
							<select id="DateofBirthMonth" class="span4">
								<option>
									- Month -
								</option>
								<option value="1">
									January
								</option>
								<option value="2">
									February
								</option>
								<option value="3">
									March
								</option>
								<option value="4">
									April
								</option>
								<option value="5">
									May
								</option>
								<option value="6">
									June
								</option>
								<option value="7">
									July
								</option>
								<option value="8">
									August
								</option>
								<option value="9">
									September
								</option>
								<option value="10">
									October
								</option>
								<option value="11">
									November
								</option>
								<option value="12">
									December
								</option>
							</select>
							<select name="DateOfBirth_Day" class="span4 input-left-top-margins">
								<option>
									- Day -
								</option>
								<option value="1">
									1
								</option>
								<option value="2">
									2
								</option>
								<option value="3">
									3
								</option>
								<option value="4">
									4
								</option>
								<option value="5">
									5
								</option>
								<option value="6">
									6
								</option>
								<option value="7">
									7
								</option>
								<option value="8">
									8
								</option>
								<option value="9">
									9
								</option>
								<option value="10">
									10
								</option>
								<option value="11">
									11
								</option>
								<option value="12">
									12
								</option>
								<option value="13">
									13
								</option>
								<option value="14">
									14
								</option>
								<option value="15">
									15
								</option>
								<option value="16">
									16
								</option>
								<option value="17">
									17
								</option>
								<option value="18">
									18
								</option>
								<option value="19">
									19
								</option>
								<option value="20">
									20
								</option>
								<option value="21">
									21
								</option>
								<option value="22">
									22
								</option>
								<option value="23">
									23
								</option>
								<option value="24">
									24
								</option>
								<option value="25">
									25
								</option>
								<option value="26">
									26
								</option>
								<option value="27">
									27
								</option>
								<option value="28">
									28
								</option>
								<option value="29">
									29
								</option>
								<option value="30">
									30
								</option>
								<option value="31">
									31
								</option>
							</select>

							<select name="DateOfBirth_Year" class="span4 input-left-top-margins">
								<option>
									- Year -
								</option>
								<option value="2013">
									2011
								</option>
								<option value="2012">
									2010
								</option>
								<option value="2011">
									2011
								</option>
								<option value="2010">
									2010
								</option>
								<option value="2009">
									2009
								</option>
								<option value="2008">
									2008
								</option>
								<option value="2007">
									2007
								</option>
								<option value="2006">
									2006
								</option>
								<option value="2005">
									2005
								</option>
								<option value="2004">
									2004
								</option>
								<option value="2003">
									2003
								</option>
								<option value="2002">
									2002
								</option>
								<option value="2001">
									2001
								</option>
								<option value="2000">
									2000
								</option>
								<option value="1999">
									1999
								</option>
								<option value="1998">
									1998
								</option>
								<option value="1997">
									1997
								</option>
								<option value="1996">
									1996
								</option>
								<option value="1995">
									1995
								</option>
								<option value="1994">
									1994
								</option>
								<option value="1993">
									1993
								</option>
								<option value="1992">
									1992
								</option>
								<option value="1991">
									1991
								</option>
								<option value="1990">
									1990
								</option>
							</select>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label" for="country">
							Country/Region
						</label>
						<div class="controls">
							<select id="country" class="span12">
								<option value="">
									Country...
								</option>
								<option value="Afganistan">
									Afghanistan
								</option>
								<option value="Albania">
									Albania
								</option>
								<option value="Australia">
									Australia
								</option>
								<option value="Austria">
									Austria
								</option>
								<option value="Azerbaijan">
									Azerbaijan
								</option>
								<option value="Barbados">
									Barbados
								</option>
								<option value="Belarus">
									Belarus
								</option>
								<option value="Belgium">
									Belgium
								</option>
								<option value="Belize">
									Belize
								</option>
								<option value="Chile">
									Chile
								</option>
								<option value="China">
									China
								</option>
								<option value="Comoros">
									Comoros
								</option>
								<option value="Congo">
									Congo
								</option>
								<option value="Egypt">
									Egypt
								</option>
								<option value="El Salvador">
									El Salvador
								</option>
								<option value="Equatorial Guinea">
									Equatorial Guinea
								</option>
								<option value="Georgia">
									Georgia
								</option>
								<option value="Germany">
									Germany
								</option>
								<option value="Ghana">
									Ghana
								</option>
								<option value="Hawaii">
									Hawaii
								</option>
								<option value="Honduras">
									Honduras
								</option>
								<option value="Hong Kong">
									Hong Kong
								</option>
								<option value="Iceland">
									Iceland
								</option>
								<option value="India">
									India
								</option>
								<option value="Indonesia">
									Indonesia
								</option>
								<option value="Zambia">
									Zambia
								</option>
								<option value="Zimbabwe">
									Zimbabwe
								</option>
							</select>
						</div>
					</div>
					<div class="control-group">
						<div class="controls">
							<label class="checkbox">
								<input type="checkbox" value="a">
								Yes! I would like to receive email relating to products and services.
							</label>
							<label class="checkbox">
								<input type="checkbox" value="b">
								Remember Me :)
							</label>
						</div>
					</div>
					<div class="form-actions no-margin">
						<button type="submit" class="btn btn-info pull-right">
							Create Account
						</button>
						<div class="clearfix">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="span6">
		<div class="widget">
			<div class="widget-header">
				<div class="title">
					<span class="fs1" aria-hidden="true" data-icon="&#xe14a;"></span> Event Registration
				</div>
			</div>
			<div class="widget-body">
				<form class="form-horizontal no-margin">
					<div class="control-group">
						<label class="control-label">
							First Name
						</label>
						<div class="controls controls-row">
							<input class="span12" type="text" placeholder="First Name">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">
							Last Name
						</label>
						<div class="controls controls-row">
							<input class="span12" type="text" placeholder="Last Name">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">
							No. of Persons
						</label>
						<div class="controls controls-row">
							<input class="span12" type="number" placeholder="Number of persons attending">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="stateAndCity">
							State and City
						</label>
						<div class="controls">
							<select id="stateAndCity" class="span6">
								<option value="" selected="selected">
									Select a State
								</option>
								<option value="AL">
									Alabama
								</option>
								<option value="AK">
									Alaska
								</option>
								<option value="AZ">
									Arizona
								</option>
								<option value="AR">
									Arkansas
								</option>
								<option value="CA">
									California
								</option>
								<option value="CO">
									Colorado
								</option>
								<option value="CT">
									Connecticut
								</option>
								<option value="DE">
									Delaware
								</option>
								<option value="DC">
									District Of Columbia
								</option>
								<option value="FL">
									Florida
								</option>
								<option value="GA">
									Georgia
								</option>
								<option value="HI">
									Hawaii
								</option>
								<option value="ID">
									Idaho
								</option>
								<option value="IL">
									Illinois
								</option>
								<option value="IN">
									Indiana
								</option>
								<option value="IA">
									Iowa
								</option>
								<option value="KS">
									Kansas
								</option>
								<option value="KY">
									Kentucky
								</option>
								<option value="LA">
									Louisiana
								</option>
								<option value="ME">
									Maine
								</option>
								<option value="MD">
									Maryland
								</option>
								<option value="MA">
									Massachusetts
								</option>
								<option value="MI">
									Michigan
								</option>
								<option value="MN">
									Minnesota
								</option>
								<option value="MS">
									Mississippi
								</option>
								<option value="MO">
									Missouri
								</option>
								<option value="MT">
									Montana
								</option>
								<option value="NE">
									Nebraska
								</option>
								<option value="NV">
									Nevada
								</option>
								<option value="NH">
									New Hampshire
								</option>
								<option value="NJ">
									New Jersey
								</option>
								<option value="NM">
									New Mexico
								</option>
								<option value="NY">
									New York
								</option>
								<option value="NC">
									North Carolina
								</option>
								<option value="ND">
									North Dakota
								</option>
								<option value="OH">
									Ohio
								</option>
								<option value="OK">
									Oklahoma
								</option>
								<option value="OR">
									Oregon
								</option>
								<option value="PA">
									Pennsylvania
								</option>
								<option value="RI">
									Rhode Island
								</option>
								<option value="SC">
									South Carolina
								</option>
								<option value="SD">
									South Dakota
								</option>
								<option value="TN">
									Tennessee
								</option>
								<option value="TX">
									Texas
								</option>
								<option value="UT">
									Utah
								</option>
								<option value="VT">
									Vermont
								</option>
								<option value="VA">
									Virginia
								</option>
								<option value="WA">
									Washington
								</option>
								<option value="WV">
									West Virginia
								</option>
								<option value="WI">
									Wisconsin
								</option>
								<option value="WY">
									Wyoming
								</option>
							</select>
							<select name="City" class="span6 input-left-top-margins">
								<option value="" selected="selected">
									Select a City
								</option>
								<option value="AL">
									Alabama
								</option>
								<option value="AK">
									Alaska
								</option>
								<option value="AZ">
									Arizona
								</option>
								<option value="AR">
									Arkansas
								</option>
								<option value="CA">
									California
								</option>
								<option value="CO">
									Colorado
								</option>
								<option value="CT">
									Connecticut
								</option>
								<option value="DE">
									Delaware
								</option>
								<option value="DC">
									District Of Columbia
								</option>
								<option value="FL">
									Florida
								</option>
								<option value="IL">
									Illinois
								</option>
								<option value="IN">
									Indiana
								</option>
								<option value="IA">
									Iowa
								</option>
								<option value="KS">
									Kansas
								</option>
								<option value="KY">
									Kentucky
								</option>
								<option value="LA">
									Louisiana
								</option>
								<option value="ME">
									Maine
								</option>
								<option value="NV">
									Nevada
								</option>
								<option value="NH">
									New Hampshire
								</option>
								<option value="NJ">
									New Jersey
								</option>
								<option value="NM">
									New Mexico
								</option>
								<option value="NY">
									New York
								</option>
								<option value="NC">
									North Carolina
								</option>
								<option value="ND">
									North Dakota
								</option>
								<option value="OH">
									Ohio
								</option>
								<option value="OK">
									Oklahoma
								</option>
								<option value="OR">
									Oregon
								</option>
								<option value="PA">
									Pennsylvania
								</option>
								<option value="RI">
									Rhode Island
								</option>
							</select>
						</div>
					</div>
					<div class="control-group info">
						<label class="control-label">
							Zip Code
						</label>
						<div class="controls">
							<input class="span4" type="text" placeholder="Zip Code">
							<span class="help-inline ">
								Enter your Zip Code
							</span>
						</div>
					</div>
					<div class="control-group error">
						<label class="control-label">
							Phone
						</label>
						<div class="controls">
							<input class="span4" type="text" placeholder="Phone">
							<span class="help-inline ">
								Enter your Phone Number
							</span>
						</div>
					</div>
					<div class="control-group success">
						<label class="control-label" for="email2">
							Email
						</label>
						<div class="controls">
							<div class="input-prepend">
								<span class="add-on">
									@
								</span>
								<input class="span12" id="email2" type="text" placeholder="Email">
							</div>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">
							Address
						</label>
						<div class="controls">
							<textarea class="input-block-level no-margin" style="height: 75px"></textarea>
						</div>
					</div>
					<div class="form-actions no-margin">
						<button type="submit" class="btn btn-info pull-right">
							Registar
						</button>
						<div class="clearfix">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?= $this->endSection(); ?>