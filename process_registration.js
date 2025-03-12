function validate(form)
      {
		//Checks if any error messages have been returned from validation functions
		fail = validateFirstName(form.firstName.value, form.firstName.value)
		fail += validateEmail(form.email.value)
		fail += validateLastName(form.lastName.value, form.lastName.value)
		fail += validatePhone(form.phone.value, form.phone.value)
        fail += validateUsername(form.username.value)
        fail += validatePassword(form.password.value, form.confirm_password.value)
		fail += validateSecurityAnswers(form.security_answer_1.value, form.security_answer_2.value)

		//If fail is blank return true, otherwise return false and display error message
        if (fail == ""){
			return true
		}
        else {
			alert(fail); 
			return false 
			}
      }
	  
	  //Function checks first name field
	  function validateFirstName(fn)
	  {
		  if (fn == ""){
			  return "No first name was entered.\n"
		  }
	  }
	  
	  //Function checks email entry from form
      function validateEmail(em)
      {
        if (em == ""){
			return "No Email was entered.\n"
		}
		else if (!((em.indexOf(".") > 0) &&
                     (em.indexOf("@") > 0)) ||
                     /[^a-zA-Z0-9.@_-]/.test(em)){
						 return "The Email address is invalid.\n"
					 }
        return ""
      }
	  
	  //Function checks last name field
	  function validateLastName(ln)
	  {
		  if (fn == ""){
			  return "No last name was entered.\n"
		  }
	  }
	  
	  //Function checks phone number field
	  function validatePhone(pn)
	  {
		  if (pn == ""){
			  return "No phone number was entered.\n"
		  }
		  else if (/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/.test(pn)){
			  return "The phone number must be written as ###-###-#### format.";
		  }
	  }


	  //Function checks username entry from form
      function validateUsername(un)
      {
        if (un == ""){
			return "No Username was entered.\n"
		}
        else if (un.length < 5){
			return "Usernames must be at least 5 characters.\n"
		}
        else if (/[^a-zA-Z0-9_-]/.test(un)){
			return "Only a-z, A-Z, 0-9, - and _ allowed in Usernames.\n"
		}
        return ""
      }
	  
	  //Function checks password and confirmed password from form
      function validatePassword(pwd, con_pwd)
      {
        if (pwd == ""){
			return "No Password was entered.\n"
		}
        else if (pwd.length < 6){
			return "Passwords must be at least 6 characters.\n"
		}
        else if (! /[a-z]/.test(pwd) ||
                 ! /[A-Z]/.test(pwd) ||
                 ! /[0-9]/.test(pwd)){
					 return "Passwords require one each of a-z, A-Z and 0-9.\n"
				 }
		else if (pwd !== con_pwd){
			return "The passwords do not match.\n"
		}
        return ""
      }
	  
	  //Function checks security answer 1 and security answer 2
	  function validateSecurityAnswers(s1, s2){
		  if (s1 == "")
		  {
			  return "No answer given for security question 1.";
		  }
		  else if (s2 == "")
		  {
			  return "No answer given for security question 2.";
		  }
		  
	  }
