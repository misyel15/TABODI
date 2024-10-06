document.body.onload = () => {
let input = document.querySelector('.certificata');
findCourse = () => {
switch(input.value) {
	
case 'Barangay Clearance':
window.open('../print/certificate.php', '_self');
break;

case 'About':
window.open('About.html', '_self');
break;

case 'Contact':
window.open('Contact.html', '_self');
break;

case 'MCC Goal':
window.open('MCCGoal.html', '_self');
break;

case 'Mission & Vision':
window.open('Mission&Vision.html', '_self');
break;

case 'CBRC':
window.open('CBRC.html', '_self');
break;

case 'Facilities':
window.open('Facilities.html', '_self');
break;

case 'Excellent Students':
window.open('ExcellentStudents.html', '_self');
break;

case 'Organizational Chart':
window.open('OrganizationalChart.html', '_self');
break;

case 'Board of Trustees':
window.open('BoardOfTrustees.html', '_self');
break;

case 'Supreme Student Council':
window.open('SupremeStudentCouncil.html', '_self');
break;

case 'Community Engagement':
window.open('CommunityEngagement.html', '_self');
break;

case 'School Calendar':
window.open('SchoolCalendar.html', '_self');
break;

case 'Downloads':
window.open('Downloads.html', '_self');
break;

case 'Online Enrollment':
window.open('OnlineEnrollment.html', '_self');
break;

case 'Tertiary Distribution':
window.open('TertiaryDistribution.html', '_self');
break;

case 'Educational Assistance':
window.open('EducationalAssistance.html', '_self');
break;

default: 
alert('File not found.');
break;

    }
  }

input.addEventListener('change', function() {
switch(input.value) {
	
case 'Home':
window.open('index.html', '_self');
break;
	
case 'About':
window.open('About.html', '_self');
break;

case 'Contact':
window.open('Contact.html', '_self');
break;

case 'MCC Goal':
window.open('MCCGoal.html', '_self');
break;

case 'Mission & Vision':
window.open('Mission&Vision.html', '_self');
break;

case 'CBRC':
window.open('CBRC.html', '_self');
break;

case 'Facilities':
window.open('Facilities.html', '_self');
break;

case 'Excellent Students':
window.open('ExcellentStudents.html', '_self');
break;

case 'Organizational Chart':
window.open('OrganizationalChart.html', '_self');
break;

case 'Board of Trustees':
window.open('BoardOfTrustees.html', '_self');
break;

case 'Supreme Student Council':
window.open('SupremeStudentCouncil.html', '_self');
break;

case 'Community Engagement':
window.open('CommunityEngagement.html', '_self');
break;

case 'School Calendar':
window.open('SchoolCalendar.html', '_self');
break;

case 'Downloads':
window.open('Downloads.html', '_self');
break;

case 'Online Enrollment':
window.open('OnlineEnrollment.html', '_self');
break;

case 'Tertiary Distribution':
window.open('TertiaryDistribution.html', '_self');
break;

case 'Educational Assistance':
window.open('EducationalAssistance.html', '_self');
break;

default: 
alert('File not found.');
break;

    }
}

)

}