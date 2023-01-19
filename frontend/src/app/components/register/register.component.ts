import { Component, OnInit} from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { User } from '../../models/user';
import { UserService } from '../../services/user.service';
import { HttpClient } from '@angular/common/http';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.scss']
})
export class RegisterComponent implements OnInit {
  public user: User;
  public forma: FormGroup;
  public status: string;

  constructor(
    private fb: FormBuilder,
    private _userService: UserService,
    private _http: HttpClient
  ) 
  {
    this.user = new User(1,'','','ROLE_USER', '','','','');
    this.createForm();

  }

  ngOnInit(): void 
  {
   
  }


  get nombreNoValido() 
  {
    return this.forma.get('name').invalid && this.forma.get('name').touched
  }
  
  get surnameNoValido() 
  {
    return this.forma.get('surname').invalid && this.forma.get('surname').touched
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

  onSubmit(user)
  {
    console.log( this.forma );
  

    if ( this.forma.invalid ) 
    {
      console.log(this.forma.invalid);

      return Object.values( this.forma.controls ).forEach( control => {

        if ( control instanceof FormGroup ) 
        {
          Object.values( control.controls ).forEach( control => control.markAsTouched() );
        } else 
        {
          control.markAsTouched();
        }
      });
    }
    else 
    {
      this.user = this.forma.value;
      this._userService.register(this.user).subscribe(
      {
        next: (response) =>
        {
          console.log(response);
          console.log(this.forma);

          if(response.status == "success")
          {
            this.status = response.status;
            this.forma.reset();
          }
          else 
          {
            this.status = 'error';
            console.log("error status", this.status);
            
          }
          
          },
          error: (error) =>
          {
            console.log(<any>error);
          }
      
        });


    }

  }
}
