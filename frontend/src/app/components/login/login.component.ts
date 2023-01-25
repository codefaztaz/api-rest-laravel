import { Component, OnInit } from '@angular/core';
import { User } from '../../models/user';
import { UserService } from '../../services/user.service';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {
  public user: User;
	public status: string;
	public token;
	public identity;
  public forma: FormGroup;

  constructor(
		private _userService: UserService,
		private _router: Router,
		private _route: ActivatedRoute,
    private fb: FormBuilder,
	) {
		
		this.user = new User(1, '', '', 'ROLE_USER', '', '', '', '');
    this.createForm();
	}


  ngOnInit() 
  {
		// Se ejecuta siempre y cierra sesión solo cuando le llega el parametro sure por la url
		this.logout();
	}


  get emailNoValido() 
  {
    return this.forma.get('email').invalid && this.forma.get('email').touched
  }

  get passwordNoValido() 
  {
    return this.forma.get('password').invalid && this.forma.get('password').touched
  }

  createForm() 
  {

      this.forma = this.fb.group({
        name : ['', Validators.required ],
        surname : ['', Validators.required ],
        email : ['', [Validators.required,Validators.pattern("^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$")]],
        password : ['', Validators.required] ,

    
      });
    
    
  }  

 onSubmit(form)
 {
    this.user = this.forma.value;
		this._userService.signup(this.user).subscribe(
    {
        next: (response) =>
        {

        
          // TOKEN
          if(response.status != 'error')
          {
            this.status = 'success';
            this.token = response;

            // OBJETO USUARIO IDENTIFICADO
            this._userService.signup(this.user, true).subscribe(
              response => 
              {
                this.identity = response;

                // PERSISTIR DATOS USUARIO IDENTIFICADO
                console.log(this.token);
                console.log(this.identity);

                localStorage.setItem('token', this.token);
                localStorage.setItem('identity', JSON.stringify(this.identity));

                // Redirección a inicio
                this._router.navigate(['inicio']);
              },
              error => 
              {
                this.status = 'error';
                console.log(<any>error);
              }
            );

          }


				},
        error: (error) =>
        {
          console.log(<any>error);
        }
     
    
      });


	}

	logout()
  {
		this._route.params.subscribe(params => 
      {
			let logout = +params['sure'];

			if(logout == 1)
      {
				localStorage.removeItem('identity');
				localStorage.removeItem('token');

				this.identity = null;
				this.token = null;

				// Redirección a inicio
				this._router.navigate(['inicio']);
			}
		});
	}



}
