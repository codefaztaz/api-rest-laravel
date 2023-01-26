import { Component, OnInit} from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { User } from '../../models/user';
import { UserService } from '../../services/user.service';
import { HttpClient } from '@angular/common/http';

@Component({
  selector: 'app-user-edit',
  templateUrl: './user-edit.component.html',
  styleUrls: ['./user-edit.component.scss']
})
export class UserEditComponent implements OnInit {

  public user: User;
  public forma: FormGroup;
  public status: string;
  public token;
	public identity;
  public id;

  constructor(
    private fb: FormBuilder,
    private _userService: UserService,
    private _http: HttpClient,
    private _router: Router,
		private _route: ActivatedRoute,
    
  ) 
  {
    this.user = new User(1,'','','ROLE_USER', '','','','');
    this.createForm();
    this.identity = this._userService.getIdentity();
    console.log("identity", this.identity.sub);
    this.token = this._userService.getToken();

    // rellenar objeto usuario
    //this.forma = this.identity;
    this.getUser();



  }

  ngOnInit(): void {
    
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

  get descriptionNoValido() 
  {
    return this.forma.get('description').invalid && this.forma.get('description').touched
  }

  createForm() 
  {

      this.forma = this.fb.group({
        name : ['', Validators.required ],
        surname : ['', Validators.required ],
        email : ['', [Validators.required,Validators.pattern("^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$")]],
        description : ['', Validators.required] ,

    
      });
    
    
  }

  getUser()
  {

     
    this._route.params.subscribe(params =>{
      let id = params['sub'];
      //this.id = this.identity.sub;
      console.log('problema',id);
      this._userService.getUser(id).subscribe(
        response =>
        {
          if(!response.user)
          {
            this._router.navigate(['/inicio']);
          }
   
          else
          {
            this.user = this.identity;
          //  this.forma.controls['_id'].setValue(response.user.sub);
            this.forma.controls['name'].setValue(response.user.name);
            this.forma.controls['surname'].setValue(response.user.surname);
            this.forma.controls['email'].setValue(response.user.email);
            this.forma.controls['description'].setValue(response.user.description);
            this.forma.controls['image'].setValue(response.user.image);
          
         
        
          }
        }
      )
      });
  }
  
  onSubmit(user)
  {

  }


}
