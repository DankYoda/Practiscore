import { Component } from '@angular/core';
import { RouterLink } from '@angular/router';
import { MatButtonModule } from '@angular/material/button';

@Component({
  selector: 'app-not-found',
  imports: [RouterLink, MatButtonModule],
  templateUrl: './not-found.html',
  styleUrl: './not-found.css',
})
export class NotFound {}
