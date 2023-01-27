import { Component, OnInit,DoCheck} from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { AngularFileUploaderConfig } from 'angular-file-uploader';
import { User } from '../../models/user';
import { UserService } from '../../services/user.service';
import { HttpClient } from '@angular/common/http';
import { global } from '../../services/global';

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
  public url;
  public data;


  public froala_options: Object = {
    charCounterCount: true,
    language: 'es',
    toolbarButtons: ['bold', 'italic', 'underline', 'paragraphFormat'],
    toolbarButtonsXS: ['bold', 'italic', 'underline', 'paragraphFormat'],
    toolbarButtonsSM: ['bold', 'italic', 'underline', 'paragraphFormat'],
    toolbarButtonsMD: ['bold', 'italic', 'underline', 'paragraphFormat'],
};
 
public afuConfig:AngularFileUploaderConfig = {
  multiple: false,
  formatsAllowed: ".jpg, .png, .gif, .jpeg",
  maxSize: 0.5,
  uploadAPI:  {
    url: global.url+'user/upload',
    headers: {
     "Authorization": this._userService.getToken() 
    },
 
    
  },
  theme: "attachPin",
  hideProgressBar: false,
  hideResetBtn: true,
  hideSelectBtn: false,
};
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
    console.log("identity prueba", this.identity);
    console.log("identity", this.identity.sub);
    this.token = this._userService.getToken();
    this.url = global.url;

    
    this.getUser();
   
    this.user = new User(
			this.identity.id, 
			this.identity.name,
			this.identity.surname, 
			this.identity.role, 
			this.identity.email, '', 
			this.identity.description,
			this.identity.image
		);
    console.log('user mierda',this.user);




  }

  ngOnInit(): void {
    this.identity = this._userService.getIdentity();
    

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
        id : ['', ],
        name : ['', Validators.required ],
        surname : ['', Validators.required ],
        email : ['', [Validators.required,Validators.pattern("^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$")]],
        description : ['', Validators.required] ,
        image:['']

    
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
           // this.user = this.identity;
            console.log(this.user);
            this.forma.controls['id'].setValue(response.user.id);
            this.forma.controls['name'].setValue(response.user.name);
            this.forma.controls['surname'].setValue(response.user.surname);
            this.forma.controls['email'].setValue(response.user.email);
            this.forma.controls['description'].setValue(response.user.description);
           // this.forma.controls['image'].setValue(response.user.image);
           
          
    
        
          }
        }
      )
      });
  }
  
  onSubmit(user)
  {
    
    this.user = this.forma.value;
    this._userService.update(this.token, this.user).subscribe(
    {

      
		  next:	response => 
      {
        console.log("estoy aki");
          
       

        this.user.id =  this.forma.controls['id'].value;
        this.user.name = this.forma.controls['name'].value;
        this.user.surname = this.forma.controls['surname'].value;
        this.user.email = this.forma.controls['email'].value;
        this.user.description = this.forma.controls['description'].value;
  
        if(this.user.image != null)
        {
          this.user.image = localStorage.getItem('identity'), JSON.stringify(this.identity.image);
        }
      

       this.getUser();

        this.identity = this.user;
        localStorage.setItem('identity', JSON.stringify(this.identity));
       
    
   
         

       
          
					

			
			},
      error: (error) =>
      {
				this.status = 'error';
				console.log(<any>error);
			}

    });
		

    }
    
  
    avatarUpload(data)
    {
      //let imagen = JSON.parse(data.body.image);
      //this.user.image = data.image;
      let data_image = data.body.image;
      console.log("kakak", data_image);
      this.user.image = data_image;
      this._userService.update(this.token, this.user).subscribe(
        {

      
        next:	response => 
        {
          console.log("estoy aki");
            
         
  
          this.user.id =  this.forma.controls['id'].value;
          this.user.name = this.forma.controls['name'].value;
          this.user.surname = this.forma.controls['surname'].value;
          this.user.email = this.forma.controls['email'].value;
          this.user.description = this.forma.controls['description'].value;
    
          this.user.image = data_image;
          
        
  
         this.getUser();
  
          this.identity = this.user;
          localStorage.setItem('identity', JSON.stringify(this.identity));
         
      
     
           
  
         
            
            
  
        
        },
        error: (error) =>
        {
          this.status = 'error';
          console.log(<any>error);
        }


    
      
    
     

      }); 
    }

}
