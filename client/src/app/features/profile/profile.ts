import { Component } from '@angular/core';
import { MatTabsModule } from '@angular/material/tabs';
import { ProfileDetails } from './profile-details/profile-details';
import { MyRegistrations } from './my-registrations/my-registrations';
import { MyScores } from './my-scores/my-scores';
import { MyVideos } from './my-videos/my-videos';

@Component({
  selector: 'app-profile',
  imports: [MatTabsModule, ProfileDetails, MyRegistrations, MyScores, MyVideos],
  templateUrl: './profile.html',
  styleUrl: './profile.css',
})
export class Profile {}
